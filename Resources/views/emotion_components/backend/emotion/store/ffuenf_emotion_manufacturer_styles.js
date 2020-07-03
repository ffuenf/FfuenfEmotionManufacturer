Ext.define('Shopware.apps.Emotion.store.FfuenfEmotionManufacturerStyles', {
    extend: 'Ext.data.Store',
    fields: [
        {
            name: 'id',
            type: 'integer'
        },
        {
            name: 'name',
            type: 'string'
        }
    ],
    data: [
        {
            id: 1,
            name: "Liste"
        },
        {
            id: 2,
            name: "Bilder"
        },
        {
            id: 3,
            name: "Index"
        }
    ]
});