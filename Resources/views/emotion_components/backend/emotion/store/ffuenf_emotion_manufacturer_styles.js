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
Ext.define('Shopware.apps.Emotion.store.FfuenfEmotionManufacturerHeaderType', {
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
            name: "H1"
        },
        {
            id: 2,
            name: "H2"
        },
        {
            id: 3,
            name: "H3"
        },
        {
            id: 4,
            name: "H4"
        },
        {
            id: 5,
            name: "H5"
        },
        {
            id: 6,
            name: "H6"
        },
        {
            id: 7,
            name: "div"
        }
    ]
});