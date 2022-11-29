import React from 'react';

import './Spinner.scss';

const Spinner = ({className=''}) => {
    return (
        <div className={className}>
            <div className="loading"></div>
        </div>
    )
}

export default Spinner;