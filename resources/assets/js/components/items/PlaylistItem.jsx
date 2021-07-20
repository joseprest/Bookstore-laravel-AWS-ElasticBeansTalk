const React = require('react-external');
const _ = require('lodash-external');
const Panneau = require('panneau');
const { t } = require('../../lib/trans');
const Snippets = require('../snippets/index');
const Requests = require('../../requests/index');

const BubbleSnippet = Snippets.Bubble;
const ConditionSnippet = Snippets.Condition;
const ModalActions = Panneau.Actions.Modal;
const ListActions = Panneau.Actions.List;
const AsyncTasksActions = Panneau.Actions.AsyncTasks;

const PlaylistItem = React.createClass({
    propTypes: {
        editable: React.PropTypes.bool,
        data: React.PropTypes.object.isRequired,
        index: React.PropTypes.number.isRequired,
        list: React.PropTypes.string.isRequired,
        onConditionChanging: React.PropTypes.func,
        onClickRemove: React.PropTypes.func,
        onConditionChange: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            editable: false,
            onClickRemove: null,
            onConditionChanging: null,
            onConditionChange: null,
        };
    },

    onConditionEditClick(e) {
        e.preventDefault();

        const bubble = this.props.data;
        const condition = _.get(this.props, 'data.condition', {});

        Panneau.dispatch(ModalActions.openModal('Conditions', {
            condition,
            bubbles: [bubble],
            onComplete: this.onModalComplete,
            onClose: this.onModalClose,
        }));
    },

    onModalComplete(condition) {
        Panneau.dispatch(ModalActions.closeModal('Conditions'));

        const id = _.get(this.props, 'data.condition.id', null);
        const request = Requests.Playlists.saveCondition(condition, id);

        // We temporarely set the bubble's condition to 'loading'.
        const bubble = _.get(this.props, 'data', null);
        _.set(bubble || {}, 'condition', 'loading');
        Panneau.dispatch(ListActions.updateItem(this.props.index, bubble, this.props.list));

        // Create new async task (to display the throbber)
        Panneau.dispatch(AsyncTasksActions.add(request));

        if (this.props.onConditionChanging) {
            this.props.onConditionChanging(request);
        }

        request.then((savedCondition) => {
            const updatedBubble = _.get(this.props, 'data', null);
            _.set(updatedBubble || {}, 'condition', savedCondition);

            Panneau.dispatch(
                ListActions.updateItem(this.props.index, updatedBubble, this.props.list)
            );

            if (this.props.onConditionChange) {
                this.props.onConditionChange(savedCondition);
            }
        });
    },

    onClickRemove() {
        if (this.props.onClickRemove) {
            this.props.onClickRemove(this.props.data);
        }
    },

    renderColDrag(colSize = 1) {
        const colClassName = `list-item-col-drag list-item-col-${colSize}`;
        const loading = _.get(this.props, 'data.loading', false);
        const iconClassName = loading ? 'drag-loading' : 'glyphicon glyphicon-menu-hamburger';

        if (this.props.editable) {
            return (
                <div key="col_drag" className={colClassName}>
                    <span className={iconClassName} />
                </div>
            );
        }

        return (
            <div key="col_drag" className={colClassName} />
        );
    },

    renderColSnippet(colSize = 12) {
        const colClassName = `list-item-col-${colSize}`;
        const type = _.get(this.props, 'data.bubble.type', '');
        const data = _.get(this.props, 'data.bubble.snippet');

        return (
            <div key="col_snippet" className={colClassName}>
                <BubbleSnippet
                    type={type}
                    data={data}
                    withDescription={type === 'filter'}
                />
            </div>
        );
    },

    renderColConditions(colSize = 12) {
        const colClassName = `list-item-col-${colSize}`;

        if (_.get(this.props, 'data.condition') === 'loading') {
            return (
                <div key="col_conditions" className={colClassName}>
                    <span className="inline-icon-loading" />
                    { t('slideshow.updating') }
                </div>
            );
        }

        const data = _.get(this.props, 'data.condition.snippet');
        const fields = _.get(this.props, 'data.condition.fields', {});
        const hasCondition = Object.values(fields).some((v) => {
            const isArray = Array.isArray(v);
            return (v && !isArray) || (isArray && v.length);
        });

        let condition;
        if (hasCondition) {
            condition = (
                <ConditionSnippet data={data} />
            );
        } else {
            condition = (
                <div>{ t('slideshow.always') }</div>
            );
        }

        if (this.props.editable) {
            return (
                <div key="col_conditions" className={colClassName}>
                    { condition }
                    <div>
                        <a href="#" onClick={this.onConditionEditClick}>
                            { t('general.actions.edit') }
                        </a>
                    </div>
                </div>
            );
        }

        return (
            <div key="col_conditions" className={colClassName}>
                { condition }
            </div>
        );
    },

    renderColRemove(colSize = 1) {
        const colClassName = `list-item-col-last list-item-col-${colSize}`;

        if (this.props.editable) {
            return (
                <div key="col_remove" className={colClassName}>
                    <button
                        type="button"
                        className="btn btn-default btn-no-border glyphicon glyphicon-minus-sign"
                        onClick={this.onClickRemove}
                    />
                </div>
            );
        }

        return (
            <div key="col_remove" className={colClassName} />
        );
    },

    render() {
        const cols = [
            this.renderColDrag(1),
            this.renderColSnippet(6),
            this.renderColConditions(4),
            this.renderColRemove(1),
        ];

        return (
            <div className="list-item">
                { cols }
            </div>
        );
    },
});

module.exports = PlaylistItem;
