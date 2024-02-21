import React, { useState } from 'react';
import { __ } from '@wordpress/i18n';
import { addQueryArgs } from '@wordpress/url';
import apiFetch from '@wordpress/api-fetch';

import { Button, BaseControl } from '@wordpress/components';

const SyncBtn = () => {
    const [Syncing, setSyncing] = useState(null)
    const [BtnTitle, setBtnTitle] = useState(__('Sync', 'ddemo'))

    const setSync = async () => {
        let query = {
            include: '',
            indexables: '',
            post_type: 'page',
            trigger: 'manual',
        }
        let res = await apiFetch({
            path: addQueryArgs(`/elasticpress/v1/sync`, query),
            method: 'POST'
        })
        return res;
    }
    const syncPages = async () => {
        setSyncing(true)
        try {
            let res = await setSync()
            let data = res.data
            if (res.success == true && data?.totals && Object.keys(data?.totals).length == 0) {
                //starting
                setBtnTitle(__('Syncing...', 'ddemo'))
                setTimeout(syncPages, 2000)
                return false;
            }
            else if (data?.index_meta && data.index_meta?.indexing == true) {
                //progressing
                let val = (data.index_meta.items_indexed / data.index_meta.found_items * 100).toFixed(1)
                // todo improve progress number
                if (val > 0 && val <= 100)
                    setBtnTitle(__('Syncing...', 'ddemo') + ' ' + val + '%')
                setTimeout(syncPages, 2000)
                return false;
            }
            else if (res.success == true && data.totals && Object.keys(data.totals).length > 0) {
                //finished
                setBtnTitle(__('Done!', 'ddemo'))

            }
        }
        catch (err) {
            console.log(err)
            alert(__('There was an error. Details in console', 'ddemo'))
        }
        setSyncing(false)
        setTimeout(() => {
            setBtnTitle(__('Sync', 'ddemo'))
        }, 1000)
    }
    return (
        <BaseControl>
            <Button onClick={syncPages} isBusy={Syncing} disabled={Syncing} variant="primary">{BtnTitle}</Button>
        </BaseControl>
    )
}

export default SyncBtn;