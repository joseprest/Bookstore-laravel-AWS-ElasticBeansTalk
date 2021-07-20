/* eslint-disable jsx-a11y/no-static-element-interactions */
const Panneau = require('panneau');
const React = require('react-external');
const _ = require('lodash-external');
const { t } = require('../../lib/trans');
const Snippets = require('../snippets/index');

const BubbleSnippet = Snippets.Bubble;
const ConditionSnippet = Snippets.Condition;
const ModalActions = Panneau.Actions.Modal;
const ListActions = Panneau.Actions.List;

const BubbleItem = React.createClass({
    propTypes: {
        layout: React.PropTypes.string,
        checked: React.PropTypes.bool,
        editable: React.PropTypes.bool,
        editLink: React.PropTypes.string,
        data: React.PropTypes.object.isRequired,
        index: React.PropTypes.number,
        list: React.PropTypes.string,
        onCheck: React.PropTypes.func,
        onClickAdd: React.PropTypes.func,
        onClickRemove: React.PropTypes.func,
        onConditionChange: React.PropTypes.func,
        onClickDelete: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            layout: 'bubble',
            checked: false,
            editable: false,
            editLink: '',
            index: 0,
            list: null,
            onCheck: null,
            onClickAdd: null,
            onClickRemove: null,
            onConditionChange: null,
            onClickDelete: null,
        };
    },

    getInitialState() {
        return {
            checked: this.props.checked,
        };
    },

    componentWillReceiveProps(nextProps) {
        if (this.state.checked !== nextProps.checked) {
            this.setState({
                checked: nextProps.checked,
            });
        }
    },

    onConditionEditClick(e) {
        e.preventDefault();

        const bubble = _.get(this.props, 'data');
        const condition = _.get(this.props, 'data.condition', {});

        Panneau.dispatch(ModalActions.openModal('Conditions', {
            condition,
            bubbles: [bubble],
            onComplete: this.onModalComplete,
            onClose: this.onModalClose,
        }));
    },

    onModalComplete(condition) {
        const bubble = _.get(this.props, 'data', null);
        _.set(bubble || {}, 'condition', condition);

        Panneau.dispatch(ListActions.updateItem(this.props.index, bubble, this.props.list));
        Panneau.dispatch(ModalActions.closeModal('Conditions'));

        if (this.props.onConditionChange) {
            this.props.onConditionChange(condition);
        }
    },

    onClick(e) {
        if (e.target.tagName.toLowerCase() === 'input') {
            return;
        }

        if (this.props.layout === 'add' && this.props.onCheck) {
            this.props.onCheck(!this.props.checked, this.props.data);
        }
    },

    onCheckboxChange(value) {
        if (this.props.onCheck) {
            this.props.onCheck(value, this.props.data);
        }
    },

    onClickAdd() {
        if (this.props.onClickAdd) {
            this.props.onClickAdd(this.props.data);
        }
    },

    onClickRemove() {
        if (this.props.onClickRemove) {
            this.props.onClickRemove(this.props.data);
        }
    },

    onClickDelete() {
        if (this.props.onClickDelete) {
            this.props.onClickDelete(this.props.data);
        }
    },

    renderColDrag(colSize = 1) {
        const colClassName = `list-item-col-drag list-item-col-${colSize}`;

        return (
            <div key="col_drag" className={colClassName}>
                <span className="glyphicon glyphicon-menu-hamburger" />
            </div>
        );
    },

    renderColCheckbox(colSize = 1) {
        const colClassName = `list-item-col-${colSize}`;
        const CheckboxField = Panneau.Components.Fields.Checkbox;

        return (
            <div key="col_checkbox" className={colClassName}>
                <CheckboxField checked={this.props.checked} onChange={this.onCheckboxChange} />
            </div>
        );
    },

    renderColSnippet(colSize = 12) {
        const colClassName = `list-item-col-${colSize}`;
        const data = _.get(this.props, 'data.snippet');
        const onClickDelete = this.props.editable ? () => { this.onClickDelete(); } : null;

        return (
            <div key="col_snippet" className={colClassName}>
                <BubbleSnippet
                    data={data}
                    editable={this.props.editable}
                    editLink={this.props.editLink}
                    onClickDelete={onClickDelete}
                />
            </div>
        );
    },

    renderColConditions(colSize = 12) {
        const colClassName = `list-item-col-top list-item-col-${colSize}`;
        const data = _.get(this.props, 'data.condition.snippet');
        const fields = _.get(this.props, 'data.condition.fields', {});

        const hasCondition = Object.values(fields).some(
            v => (Array.isArray(v) ? (v.length > 0) : !!v)
        );

        let condition;
        if (hasCondition) {
            condition = (
                <ConditionSnippet data={data} />
            );
        } else {
            condition = (
                <p>{ t('slideshow.always') }</p>
            );
        }

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
    },

    renderColActions(colSize = 1) {
        const colClassName = `list-item-col-last list-item-col-${colSize}`;

        return (
            <div key="col_actions" className={colClassName}>
                <button type="button" className="btn btn-default" onClick={this.onClickAdd}>
                    { t('general.actions.add') }
                </button>
            </div>
        );
    },

    renderColRemove(colSize = 1) {
        const colClassName = `list-item-col-last list-item-col-${colSize}`;

        return (
            <div key="col_remove" className={colClassName}>
                <button
                    type="button"
                    className="btn btn-default btn-no-border glyphicon glyphicon-minus-sign"
                    onClick={this.onClickRemove}
                />
            </div>
        );
    },

    render() {
        let cols = [];

        if (this.props.layout === 'playlist') {
            cols = [
                this.renderColDrag(1),
                this.renderColSnippet(6),
                this.renderColConditions(4),
                this.renderColRemove(1),
            ];
        } else if (this.props.layout === 'add') {
            cols = [
                this.renderColCheckbox(1),
                this.renderColSnippet(11),
            ];
        } else {
            cols = [
                this.renderColSnippet(),
            ];
        }

        return (
            <div className="list-item" onClick={this.onClick}>
                { cols }
            </div>
        );
    },
});

module.exports = BubbleItem;
