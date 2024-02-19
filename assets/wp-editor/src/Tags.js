import React, { useState, useEffect } from 'react';
import Select from 'react-select'
import { __ } from '@wordpress/i18n';
import { useSelect } from "@wordpress/data";
import { request } from './functions/functions';

/**
 * PluginDocumentSettingPanel for tags (not used because default wp component is active)
 *
 * @returns {Function} Component element.
 */
const Tags = () => {
    const [Loading, setLoading] = useState(false);
    const postId = useSelect(select => select('core/editor').getCurrentPostId());
    const assign_tags = async (tags) => {
        let params = {
            'action': 'ddemo_set_tags',
            'post_id': postId,
            'tags': tags.map(el => el.value).join()
        }
        setLoading(true)
        let res = await request(params)
        setLoading(false)
    }
    const onChange = (value) => {
        assign_tags(value)
    }
    return (
        <div className={`dd-widget ${Loading ? 'dd-loading' : ''}`}>
            <div className='dd-widget__wrapper'>
                <div className='dd-widget__content'>
                    <Select
                        defaultValue={ddemo_vars.post_tags}
                        options={ddemo_vars.tags}
                        isMulti
                        isLoading={Loading}
                        isDisabled={Loading}
                        onChange={onChange}
                    />
                </div>
            </div>
        </div>
    )
}

export default Tags; 