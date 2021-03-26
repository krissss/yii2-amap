/**
 * 点标记 + 信息窗体
 * 使用：
 * const preset = new MarkerPreset(map)
 * preset.addMarker({position: [116.44925, 39.883874], info: {title: 'MyTitle', content: 'My Content', anyKey: 'anyValue'}, template: '<h3>{title}</h3><p>{content}{anyValue}</p>'})
 * 参考：
 * @link https://lbs.amap.com/demo/javascript-api/example/infowindow/add-infowindows-to-multiple-markers
 */
class MarkerPreset {
    constructor(map, options = {}) {
        this.map = map
        this.options = Object.assign({
            infoWindowOptions: {
                anchor: 'bottom-center',
                offset: new AMap.Pixel(0, -30)
            },
            markerOptions: {}
        }, options)

        this.infoWindow = null
    }

    addMarker({position, info, template}) {
        const marker = new AMap.Marker(Object.assign({
            map: this.map,
            position: position
        }, this.options.markerOptions))

        if (info) {
            if (this.infoWindow === null) {
                this.initInfoWindow();
            }
            if (!template) {
                template = '{content}'
            }
            if (typeof (info) === 'string') {
                info = {content: info}
            } else if (Array.isArray(info)) {
                info = {content: info.join('<br/>')}
            }
            for (const key of Object.keys(info)) {
                const value = info[key]
                template = template.replace(new RegExp('{' + key + '}', 'gms'), value)
            }
            marker.content = template
            marker.on('click', (e) => {
                if (!this.infoWindow) {
                    return
                }
                this.infoWindow.setContent(e.target.content)
                this.infoWindow.open(this.map, e.target.getPosition())
            });
        }
    }

    initInfoWindow() {
        this.infoWindow = new AMap.InfoWindow(this.options.infoWindowOptions);
    }
}
