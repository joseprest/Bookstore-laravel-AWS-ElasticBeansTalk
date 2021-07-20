const React = require('react-external');
const Immutable = require('immutable-external');
const _ = require('lodash-external');
const { t } = require('../../lib/trans');

const OrganisationsField = React.createClass({
    propTypes: {
        name: React.PropTypes.string,
        label: React.PropTypes.string,
        value: React.PropTypes.array,
        onChange: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            name: null,
            label: null,
            value: null,
            onChange: null,
        };
    },

    onClickRemove(e, organisation, index) {
        e.preventDefault();

        // eslint-disable-next-line no-alert
        if (!confirm(t('fields.organisations.remove_confirmation'))) {
            return;
        }

        const currentValue = Immutable.fromJS(this.props.value || []);
        const newValue = currentValue.delete(index);

        if (currentValue !== newValue) {
            const value = newValue.toJS();

            if (this.props.onChange) {
                this.props.onChange(value);
            }
        }
    },

    renderOrganisation(organisation, index) {
        const onClickRemove = (e) => {
            this.onClickRemove(e, organisation, index);
        };

        const role = _.get(organisation, 'pivot.role.name');
        const name = `${this.props.name}[]`;
        const key = `organisation_${index}`;
        const editURL = URL.route('organisation.edit', {
            organisation: organisation.slug,
        });

        return (
            <div key={key} className="list-item">
                <div className="list-item-col list-item-col-icon">
                    <span className="glyphicon glyphicon-lock" />
                </div>
                <div className="list-item-col">
                    { organisation.name }
                    <input type="hidden" name={name} value={organisation.id} />
                </div>
                <div className="list-item-col list-item-col-role">
                    { role }
                </div>
                <div className="list-item-col list-item-col-actions list-item-col-last">
                    <div className="btn-group">
                        <a href={editURL} type="button" className="btn btn-default">
                            <span className="glyphicon glyphicon-pencil" />
                        </a>
                        <button type="button" className="btn btn-default" onClick={onClickRemove}>
                            <span className="glyphicon glyphicon-minus-sign" />
                        </button>
                    </div>
                </div>
            </div>
        );
    },

    render() {
        let label;
        if (this.props.label) {
            label = (
                <label htmlFor={this.props.name} className="control-label">
                    { this.props.label }
                </label>
            );
        }

        let organisations;
        if (Array.isArray(this.props.value) && this.props.value.length) {
            organisations = this.props.value.map(this.renderOrganisation);
        } else {
            organisations = (
                <div className="list-item list-item-empty">
                    { t('fields.organisations.empty') }
                </div>
            );
        }

        return (
            <div className="form-group form-group-organisations">
                { label }
                <div className="list list-rows list-organisations">
                    { organisations }
                </div>
            </div>
        );
    },
});

module.exports = OrganisationsField;
