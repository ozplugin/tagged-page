import React, { useState, useEffect } from 'react';
import Select from 'react-select'
import { __ } from '@wordpress/i18n';
import { useSelect, useDispatch } from "@wordpress/data";
import List from './List';
import { request } from './functions/functions';
import { addQueryArgs } from '@wordpress/url';
import apiFetch from '@wordpress/api-fetch';

const tags = ddemo_vars.tags

/**
 * Related pages interface
 *
 * @returns {Function} Component element.
 */
const Pages = () => {
    const [Loading, setLoading] = useState(false);
    const [Selected_tags, setSelected_tags] = useState([]);
    const [Results, setResults] = useState([]);
    const postId = useSelect(select => select('core/editor').getCurrentPostId());
    const startSearch = async () => {
        let tags = Selected_tags.length ? Selected_tags.map(el => el.value) : []
        let params = {
            'action': 'ddemo_get_pages',
            'post_id': postId,
        }
        if (tags.length > 0) {
            params['tags'] = tags.join()
        }
        setLoading(true)

        //todo use apiFetch if possible
        // apiFetch({
        // 	path: addQueryArgs(`/elasticpress/v1/related-posts/${postId}`, {number: 100}),
        //     method: 'POST',
        // })

        let res = await request(params)
        setLoading(false)
        if (res && res.success) {
            setResults(res.payload)
        }
        else {
            setResults([])
        }
    }

    const onChange = (value) => {
        setSelected_tags(value)
    }

    useEffect(() => {
        startSearch()
    }, [Selected_tags])

    useEffect(() => {
        startSearch()
    }, [])

    return (
        <>
            <div className={`dd-widget ${Loading ? 'dd-loading' : ''}`}>
                <div className='dd-widget__wrapper'>
                    <div className='dd-widget__content'>
                        {tags && tags.length > 0 ? <>
                            <Select
                                options={ddemo_vars.tags}
                                isMulti
                                isLoading={Loading}
                                isDisabled={Loading}
                                onChange={onChange}
                                placeholder={__('Search pages by tag', 'ddemo')}
                            />
                            <List {...{ tags: Selected_tags, result: Results, loading: Loading }} />
                        </>
                            : <>
                                {__('You haven\'t added any tags. ', 'ddemo')}
                                <a href={`${ddemo_vars.admin_url}edit-tags.php?taxonomy=${ddemo_vars.tag_name}&post_type=page`}>{__('Add first', 'ddemo')}</a>
                            </>
                        }
                    </div>
                </div>
            </div>
        </>
    );
}

export default Pages; 