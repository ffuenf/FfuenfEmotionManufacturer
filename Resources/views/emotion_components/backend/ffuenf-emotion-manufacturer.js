// {namespace name="backend/emotion/ffuenf_emotion_manufacturer"}
//{block name="emotion_components/backend/ffuenf_emotion_manufacturer"}
Ext.define('Shopware.apps.Emotion.view.components.FfuenfEmotionManufacturer', {

    /**
     * Extend from the base class for the emotion components
     */
    extend: 'Shopware.apps.Emotion.view.components.Base',

    /**
     * Create the alias matching the xtype you defined in your `createEmotionComponent()` method.
     * The pattern is always 'widget.' + xtype
     */
    alias: 'widget.emotion-components-ffuenf-emotion-manufacturer',

    /**
     * Contains the translations of each input field which was created with the EmotionComponentInstaller.
     * Use the name of the field as identifier
     */
    snippets: {
        'show_header': {
            'fieldLabel': '{s name=showHeaderFieldLabel}Zeige Überschrift{/s}',
            'supportText': '{s name=headerSupportText}Zeige Überschrift für die Herstellerliste.{/s}'
        },
        'header': {
            'fieldLabel': '{s name=headerFieldLabel}Überschrift{/s}'
        },
        'category_id': {
            'fieldLabel': '{s name=categoryIdFieldLabel}Kategorie ID{/s}',
            'supportText': '{s name=categoryIdSupportText}Die ID der Kategorie aus der die Hersteller extrahiert werden sollen{/s}'
        },
        'category_id': {
            'fieldLabel': '{s name=categoryIdFieldLabel}Kategorie ID{/s}',
            'supportText': '{s name=categoryIdSupportText}Die ID der Kategorie aus der die Hersteller extrahiert werden sollen{/s}'
        },
        'sort_order': {
            'fieldLabel': '{s name=sortOrderFieldLabel}Sortierung{/s}',
            'supportText': '{s name=sortOrderSupportText}Sortierung der Hersteller{/s}'
        },
        'hide_inactive': {
            'fieldLabel': '{s name=hideInactiveFieldLabel}Ignoriere Hersteller ohne aktive Artikel{/s}',
            'supportText': '{s name=hideInactiveSupportText}Ignoriere Hersteller ohne aktive Artikel{/s}'
        },
        'landingpageLink': {
            'fieldLabel': '{s name=landingpageLinkFieldLabel}Hersteller-Landingpage URL{/s}',
            'supportText': '{s name=landingpageLinkSupportText}Hersteller-Landingpage URL{/s}'
        },
        'landingpageTitle': {
            'fieldLabel': '{s name=landingpageTitleFieldLabel}Hersteller-Landingpage Link-Titel{/s}',
            'supportText': '{s name=landingpageTitleSupportText}Hersteller-Landingpage Link-Titel{/s}'
        }
    }

});
//{/block}
