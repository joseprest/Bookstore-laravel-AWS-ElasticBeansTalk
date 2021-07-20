const React = require('react-external');
const { t } = require('../lib/trans');

function LoadingBlock() {
    return (
        <div className="loading-block">
            <div className="content">
                <p>{ t('components.loading_block.title') }</p>
                <div className="icon">
                    <span className="glyphicon glyphicon-cloud" />
                    <span className="dots">
                        <span className="dot" />
                        <span className="dot" />
                        <span className="dot" />
                    </span>
                </div>
                <p>{ t('components.loading_block.message') }</p>
            </div>
        </div>
    );
}

module.exports = LoadingBlock;
