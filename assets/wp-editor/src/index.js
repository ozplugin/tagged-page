import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { registerPlugin } from '@wordpress/plugins';
import { __ } from '@wordpress/i18n';
import Pages from './Pages';
import './style/style.scss'

registerPlugin( 'guten-plugin-new', {
    render: () => {
        return (
            <PluginDocumentSettingPanel name="page-related" title={__('Related pages', 'ddemo')}>
                <Pages/>
            </PluginDocumentSettingPanel>
        );
        },
    icon: null,
} );