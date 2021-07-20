const React = require('react-external');
const { loadGMap } = require('../lib/gmap');

const Map = React.createClass({
    propTypes: {
        apiKey: React.PropTypes.string,
        latitude: React.PropTypes.number,
        longitude: React.PropTypes.number,
        defaultLatitude: React.PropTypes.number,
        defaultLongitude: React.PropTypes.number,
        zoom: React.PropTypes.number,
        radius: React.PropTypes.number,
        marker: React.PropTypes.bool,
        draggable: React.PropTypes.bool,
        onRadiusChange: React.PropTypes.func,
        onPositionChange: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            apiKey: null,
            latitude: null,
            longitude: null,
            defaultLatitude: 45.509247,
            defaultLongitude: -73.56816,
            zoom: 14,
            radius: 0,
            marker: true,
            draggable: true,
            onRadiusChange: null,
            onPositionChange: null,
        };
    },

    componentDidMount() {
        loadGMap(this.props.apiKey).then(this.renderMap);
    },

    // eslint-disable-next-line no-unused-vars
    componentDidUpdate(prevProps, prevState) {
        if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
            return;
        }

        const center = this.getCenter();

        if (
            this.props.latitude
            && this.props.longitude
            && (
                prevProps.latitude !== this.props.latitude
                || prevProps.longitude !== this.props.longitude
            )
        ) {
            if (this.map) {
                this.map.setCenter(center);
            }

            if (this.marker) {
                this.marker.setPosition(center);
            }

            if (this.circle) {
                this.circle.setCenter(center);
            }
        }

        if (prevProps.zoom !== this.props.zoom) {
            if (this.map) {
                this.map.setZoom(this.props.zoom);
            }
        }

        if (prevProps.marker !== this.props.marker) {
            if (this.props.marker) {
                this.renderMarker();
            } else if (this.marker) {
                this.marker.setMap(null);
            }
        }

        if (prevProps.radius !== this.props.radius) {
            if (this.props.radius) {
                this.renderCircle(center);
            } else if (this.circle) {
                this.circle.setMap(null);
            }
        }
    },

    componentWillUnmount() {
        this.map = null;
    },

    onCircleRadiusChanged() {
        const radius = this.circle.getRadius();
        if (radius !== parseInt(this.props.radius, 10)) {
            if (this.props.onRadiusChange) {
                this.props.onRadiusChange(radius);
            }
        }
    },

    onCircleDrag() {
        if (this.marker) {
            this.marker.setPosition(this.circle.getCenter());
        }
    },

    onCircleDragEnd() {
        const position = this.circle.getCenter();
        if (this.props.onPositionChange) {
            this.props.onPositionChange({
                latitude: position.lat(),
                longitude: position.lng(),
            });
        }
    },

    onMarkerDrag(e) {
        if (this.circle) {
            this.circle.setCenter(e.latLng);
        }
    },

    onMarkerDragEnd(e) {
        if (this.props.onPositionChange) {
            this.props.onPositionChange({
                latitude: e.latLng.lat(),
                longitude: e.latLng.lng(),
            });
        }
    },

    getCenter() {
        return {
            lat: this.props.latitude || this.props.defaultLatitude,
            lng: this.props.longitude || this.props.defaultLongitude,
        };
    },

    map: null,
    mapNode: null,
    marker: null,
    circle: null,

    renderMap() {
        const center = this.getCenter();
        this.map = new google.maps.Map(this.mapNode, {
            center,
            zoom: this.props.zoom,
        });

        if (this.props.radius) {
            this.renderCircle(center);
        }

        if (this.props.marker) {
            this.renderMarker(center);
        }
    },

    renderMarker(position) {
        if (this.marker) {
            this.marker.setMap(this.map);
            return;
        }

        this.marker = new google.maps.Marker({
            position,
            map: this.map,
            draggable: this.props.draggable,
        });

        this.marker.addListener('drag', this.onMarkerDrag);
        this.marker.addListener('dragend', this.onMarkerDragEnd);
    },

    renderCircle(center) {
        if (this.circle) {
            this.circle.setMap(this.map);
            this.circle.setRadius(this.props.radius);
            return;
        }

        this.circle = new google.maps.Circle({
            center,
            strokeColor: '#0000FF',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#0000FF',
            fillOpacity: 0.35,
            map: this.map,
            radius: this.props.radius,
            editable: true,
            draggable: this.props.draggable,
        });

        this.circle.addListener('radius_changed', this.onCircleRadiusChanged);
        this.circle.addListener('drag', this.onCircleDrag);
        this.circle.addListener('dragend', this.onCircleDragEnd);
    },

    render() {
        return (
            <div className="map-container">
                <div ref={(node) => { this.mapNode = node; }} className="map" />
            </div>
        );
    },
});

module.exports = Map;
