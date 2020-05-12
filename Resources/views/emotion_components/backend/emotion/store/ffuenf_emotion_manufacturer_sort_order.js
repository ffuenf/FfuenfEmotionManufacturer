Ext.define('Shopware.apps.Emotion.store.FfuenfEmotionManufacturerSortOrder', {
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
            name: "nach Käufen"
        },
        {
            id: 2,
            name: "nach Name aufsteigend"
        },
        {
            id: 3,
            name: "nach Name absteigend"
        },
        {
            id: 4,
            name: "nach Zufall"
        },
        {
            id: 5,
            name: "nach neuesten Artikeln"
        },
        {
            id: 6,
            name: "nach neuesten Herstellern"
        }
    ]
});