import React from 'react';
import { __ } from '@wordpress/i18n';
import ListItem from './ListItem';

/**
 * List of pages.
 *
 * @param {object} props Component props.
 * @returns {Function} Component element.
 */
const List = (props) => {
    let list = props.result;
    let Tags = props.tags;
    let Loading = props.loading;


    if (!list || list.length < 1) {
        if (Loading) {
            list = __('Searching...', 'ddemo')
        }
        else {
            if (!Tags.length) {
                list = __('Please type a tag name to start search', 'ddemo')
            }
            else {
                list = __('No pages found with this tag(s)', 'ddemo')
            }
        }
    }
    else if (typeof list == 'object') {
        list = <ul className="dd-list">{list.map((el, i) => <ListItem key={i} item={el} />)}</ul>
    }

    return (
        <div className='dd-widget__results'>
            {list}
        </div>
    )
}

export default List; 