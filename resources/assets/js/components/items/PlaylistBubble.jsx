const React = require('react-external');
const { t } = require('../../lib/trans');

function PlaylistBubbleItem() {
    return (
        <div className="list-item">
            <div className="col-sm-1">
                <button
                    className="btn btn-default glyphicon glyphicon-menu-hamburger"
                    type="button"
                    name="button"
                />
            </div>
            <div className="col-sm-6">
                <div className="table-thumbnail" />
                <div className="table-description">
                    <h3>{ t('slideshow.auto_selected_event') }</h3>
                    <span className="table-type">{ t('slideshow.events') }</span>
                    <a className="table-link" href="#">{ t('general.actions.edit') }</a>
                </div>
            </div>
            <div className="col-sm-4">
                <span>{ t('slideshow.actions.always_show') }</span>
                <a className="table-link" href="#">{ t('general.actions.edit') }</a>
            </div>
            <div className="col-sm-1">
                <button
                    className="btn btn-default glyphicon glyphicon-minus-sign"
                    type="button"
                    name="button"
                />
            </div>
        </div>
    );
}

module.exports = PlaylistBubbleItem;
