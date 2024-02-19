/**
 * Ajax request. return object {success: bool, payload:mixed}.
 *
 * @params params Request params
 * @return object
 */
const request = async (params = {}) => {
    let body = new URLSearchParams();
    Object.entries(params).map(param => body.set(param[0], param[1]))
    body.set('_wpnonce', ddemo_vars.nonce)
    try {
        let res = await (await fetch(ddemo_vars.ajax_url, {
            method: 'post',
            body
        })).json()
        return res;
    }
    catch (err) {
        return err;
    }
}

export { request };