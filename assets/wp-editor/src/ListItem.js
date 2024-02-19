import React, { useState } from 'react';
import { useSelect, dispatch } from "@wordpress/data";
import { __ } from '@wordpress/i18n';
import { request } from './functions/functions';
// store of editor
//import { store as blockEditorStore } from '@wordpress/block-editor';


/**
 * List item
 *
 * @param {object} props Component props.
 * @returns {Function} Component element.
 */
const ListItem = (props) => {
    let item = props.item
    const [Loading, setLoading] = useState(false);
    const postId = useSelect(select => select('core/editor').getCurrentPostId());
    const blocks = useSelect(select => select('core/block-editor').getBlocks());
    const IsRelated = props.item.related


    const updateRelatedBlocks = () => {
        if (blocks) {
            blocks.map((block, index) => {
                if (block.name == 'elasticpress/related-posts') {
                    let copyBlock = wp.blocks.cloneBlock(block)
                    dispatch('core/block-editor').removeBlock(block.clientId)
                    dispatch('core/block-editor').insertBlock(copyBlock, index)
                    //todo does not work
                    // dispatch( 'core/block-editor' ).replaceBlock(
                    //     block.clientId,
                    //     copyBlock
                    //   );
                }
            })
        }
    }

    const onChange = async () => {
        let params = {
            'action': 'ddemo_set_relation',
            'post_id': postId,
            'related': item.ID
        }
        setLoading(true)
        let res = await request(params)

        // update related post ElasticPress widget
        updateRelatedBlocks()
        setLoading(false)
    }
    return (
        <li className="dd-list__item" id={`dd-page-${item.ID}`}>
            <label htmlFor={`dd-page-${item.ID}__input`}>
                <input key={item.ID} onChange={onChange} type="checkbox" disabled={Loading} defaultChecked={IsRelated} name={`dd-page-${item.ID}`} id={`dd-page-${item.ID}__input`} />
                {item.name}
            </label>
            {item.tags && Object.keys(item.tags).length > 0 ?
                <div className="dd-list__tags">
                    {item.tags.map((tag, i) => <span key={i}>{tag.label}</span>)}
                </div>
                : ''}
        </li>
    )
}

export default ListItem;