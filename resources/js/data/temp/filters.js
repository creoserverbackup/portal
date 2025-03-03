module.exports = [
    {
        name: 'Servers',
        value: 1,
        categoryId: 116,
        checked: "checked",
        blocks: [
            {
                bigColumn: false,
                name: 'brands',
                isRadio: true,
                categoryId: 116,
                value: '',
                option: [
                    {
                        name: 'Dell',
                        checked: false
                    },
                    {
                        name: 'HP',
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                name: 'product_size',
                option: [
                    {
                        name: '1U',
                        checked: false
                    },
                    {
                        name: '2U',
                        checked: false
                    },
                    {
                        name: '3U',
                        checked: false
                    },
                    {
                        name: '4U',
                        checked: false
                    },
                    {
                        name: 'Tower Server',
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                name: 'Bays',
                option: [
                    {
                        name: "SFF 2.5'",
                        checked: false
                    },
                    {
                        name: "LFF 3.5'",
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                name: 'version_type',
                notVisible: true,
                option: [
                    {
                        name: 'G10 (Dell)',
                        checked: false
                    },
                    {
                        name: 'G11 (Dell)',
                        checked: false
                    },
                    {
                        name: 'G12 (Dell)',
                        checked: false
                    },
                    {
                        name: 'G13 (Dell)',
                        checked: false
                    },
                    {
                        name: 'G14 (Dell)',
                        checked: false
                    },
                ]
            },

            {
                bigColumn: false,
                name: 'count_base',
                option: [
                    {
                        name: '3 bays',
                        checked: false
                    },
                    {
                        name: '4 - 5 bays',
                        checked: false
                    },
                    {
                        name: '6 - 8 bays',
                        checked: false
                    },
                    {
                        name: '10 - 16 bays',
                        checked: false
                    },
                    {
                        name: '24 - 36 bays',
                        checked: false
                    }
                ]
            },
            {
                bigColumn: false,
                name: 'type',
                option: [
                    {
                        name: 'Zelf samenstellen',
                        value: '1',
                        checked: false
                    },
                    {
                        name: 'Ready to go',
                        value: '2',
                        checked: false
                    },
                    {
                        name: 'Multibatch',
                        value: '4',
                        checked: false
                    }
                ]
            }
        ]
    },
    {
        name: 'Storages',
        value: 3,
        categoryId: 214,
        checked: false,
        blocks: [
            {
                bigColumn: false,
                name: 'brands',
                option: [
                    {
                        name: 'DELL',
                        checked: false
                    },
                    {
                        name: 'HP',
                        checked: false
                    },
                    {
                        name: 'Qnaps',
                        checked: false
                    }
                ]
            },
            {
                bigColumn: false,
                name: 'product_size',
                option: [
                    {
                        name: '1U',
                        checked: false
                    },
                    {
                        name: '2U',
                        checked: false
                    },
                    {
                        name: '3U',
                        checked: false
                    },
                    {
                        name: '4U',
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                name: 'Bays',
                option: [
                    {
                        name: "SFF 2.5'",
                        checked: false
                    },
                    {
                        name: "LFF 3.5'",
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                name: 'count_base',
                option: [
                    {
                        name: '3 bays',
                        checked: false
                    },
                    {
                        name: '4 - 5 bays',
                        checked: false
                    },
                    {
                        name: '6 - 8 bays',
                        checked: false
                    },
                    {
                        name: '10 - 16 bays',
                        checked: false
                    },
                    {
                        name: '24 - 36 bays',
                        checked: false
                    }
                ]
            },
            {
                bigColumn: false,
                name: 'type',
                option: [
                    {
                        name: 'Zelf samenstellen',
                        value: '1',
                        checked: false
                    },
                    {
                        name: 'Ready to go',
                        value: '2',
                        checked: false
                    },
                    {
                        name: 'Multibatch',
                        value: '4',
                        checked: false
                    }
                ]
            }
        ]
    },
    {
        name: 'Workstation',
        value: 5,
        categoryId: 233,
        checked: false,
        blocks: [
            {
                bigColumn: false,
                name: 'brands',
                option: [
                    {
                        name: 'DELL',
                        checked: false
                    },
                    {
                        name: 'HP',
                        checked: false
                    },
                    {
                        name: 'Lenovo',
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                name: 'processors',
                option: [
                    {
                        name: '1 Proccesor slot',
                        checked: false
                    },
                    {
                        name: '2 Proccesors slots',
                        checked: false
                    }
                ]
            },
            {
                bigColumn: false,
                name: 'type',
                option: [
                    {
                        name: 'Zelf samenstellen',
                        value: '1',
                        checked: false
                    },
                    {
                        name: 'Ready to go',
                        value: '2',
                        checked: false
                    },
                    {
                        name: 'Multibatch',
                        value: '4',
                        checked: false
                    }
                ]
            }
        ]
    },
    {
        name: 'Laptops',
        value: 7,
        categoryId: 267,
        checked: false,
        blocks: [
            {
                bigColumn: false,
                name: 'brands',
                option: [
                    {
                        name: 'Dell',
                        checked: false
                    }, {
                        name: 'HP',
                        checked: false
                    },
                    {
                        name: 'Lenovo',
                        checked: false
                    }
                ]
            },
            {
                bigColumn: false,
                name: 'Schermdiagonaal',
                option: [
                    {
                        name: "14 inch",
                        checked: false
                    },
                    {
                        name: "15.6 inch",
                        checked: false
                    },
                    {
                        name: "16.6 inch",
                        checked: false
                    },
                    {
                        name: "17.3 inch",
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                name: 'proccesor',
                option: [
                    {
                        name: 'i5 proccesor',
                        checked: false
                    },
                    {
                        name: 'i7 proccesor',
                        checked: false
                    },
                    {
                        name: 'i9 proccesor',
                        checked: false
                    }
                ]
            },
        ]
    },
    {
        name: 'HDD/SSD',
        value: 27,
        categoryId: 371,
        checked: false,
        blocks: [
            {
                bigColumn: false,
                name: 'brands',
                isRadio: true,
                categoryId: 27,
                option: [
                    {
                        name: 'HP',
                        checked: false
                    },
                    {
                        name: 'Dell',
                        checked: false
                    },
                    {
                        name: 'Beide',
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                name: 'label HP',
                notVisible: true,
                option: [
                    {
                        name: "Met HP label",
                        checked: false
                    },
                    {
                        name: "Zonder HP label",
                        checked: false
                    }
                ]
            },
            {
                bigColumn: false,
                name: 'sata',
                option: [
                    {
                        name: 'SAS/SATA 2.5"',
                        checked: false
                    },
                    {
                        name: 'SAS/SATA 3.5"',
                        checked: false
                    },
                    {
                        name: 'SSD',
                        checked: false
                    },
                    {
                        name: 'NVMe',
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                name: 'memory',
                option: [
                    {
                        name: '72GB - 480GB',
                        checked: false
                    },
                    {
                        name: '500GB - 1TB',
                        checked: false
                    },
                    {
                        name: '1TB - 6TB',
                        checked: false
                    },
                    {
                        name: '8TB - 10TB',
                        checked: false
                    },
                    {
                        name: '14TB - 16TB',
                        checked: false
                    },
                ]
            }
        ]
    },
    {
        name: 'Monitors',
        value: 32,
        categoryId: 376,
        checked: false,
        blocks: [
            {
                bigColumn: false,
                name: 'brands',
                option: [
                    {
                        name: 'HP',
                        checked: false
                    }
                ]
            },
            {
                bigColumn: false,
                name: 'diagonal',
                option: [
                    {
                        name: '23 inch',
                        checked: false
                    },
                    {
                        name: '24 inch',
                        checked: false
                    }
                ]
            }
        ]
    },
    {
        name: 'Parts',
        value: 9,
        categoryId: 276,
        checked: false,
        blocks: [
            {
                bigColumn: false,
                name: 'parts',
                isRadio: true,
                categoryId: 276,
                checked: false,
                option: [
                    {
                        name: 'Proccesors',
                        checked: false,
                        typeProduct: 'CPU'
                    },
                    {
                        name: 'RAM memory',
                        checked: false,
                        typeProduct: 'RAM'
                    },
                    {
                        name: 'Netwerk & Remote',
                        checked: false,
                        typeProduct: 'Net.',
                    },
                    {
                        name: 'Powersupply',
                        checked: false,
                        typeProduct: 'Power',
                    },
                    {
                        name: 'Kabels & Adapters',
                        checked: false,
                        typeProduct: 'Adapter',
                    },
                    {
                        name: 'Heatsinks / Fans',
                        checked: false,
                        typeProduct: 'Heatsink',
                    },
                    {
                        name: 'Cage & Backplane',
                        checked: false,
                        typeProduct: 'Backplane',
                    },
                    {
                        name: 'Moederbord & Barebone',
                        checked: false,
                        typeProduct: 'Board',
                    },
                    {
                        name: 'Rackrails',
                        checked: false,
                        typeProduct: 'Rack Rails',
                    },
                    {
                        name: 'CaddyTray',
                        checked: false,
                        typeProduct: 'Caddy',
                    },
                ]
            },
            {
                bigColumn: false,
                name: 'brands',
                notVisible: true,
                categoryId: 1,
                type: 'Proccesors',
                option: [
                    {
                        name: 'Dell',
                        checked: false
                    },
                    {
                        name: 'HP',
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                notVisible: true,
                name: 'CPU',
                type: 'Proccesors',
                option: [
                    {
                        name: 'X5500 - X5600 series',
                        checked: false
                    },
                    {
                        name: 'E5-1600 - E5- 2400 series',
                        checked: false
                    },
                    {
                        name: 'E5-2600 - E5-4600 series',
                        checked: false
                    },
                    {
                        name: 'Bronze - Silver series',
                        checked: false
                    },
                    {
                        name: 'Gold - Platinum series',
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                notVisible: true,
                name: 'version',
                type: 'Proccesors',
                option: [
                    {
                        name: 'V1',
                        checked: false
                    },
                    {
                        name: 'V2',
                        checked: false
                    },
                    {
                        name: 'V3',
                        checked: false
                    },
                    {
                        name: 'V4',
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                notVisible: true,
                name: 'brands',
                type: 'RAM memory',
                option: [
                    {
                        name: 'HP',
                        checked: false
                    },
                    {
                        name: 'Dell',
                        checked: false
                    },
                    {
                        name: 'Samsung',
                        checked: false
                    },
                    {
                        name: 'Hynix',
                        checked: false
                    },
                    {
                        name: 'Micron',
                        checked: false
                    },
                    {
                        name: 'Kingston',
                        checked: false
                    },
                    {
                        name: 'Nanya',
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                notVisible: true,
                name: 'HP label',
                type: 'RAM memory',
                option: [
                    {
                        name: 'Met HP label',
                        checked: false
                    },
                    {
                        name: 'Zonder HP label',
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                notVisible: true,
                name: 'version',
                type: 'RAM memory',
                option: [
                    {
                        name: 'DDR3',
                        checked: false
                    },
                    {
                        name: 'DDR4',
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                notVisible: true,
                name: 'size_ram',
                type: 'RAM memory',
                option: [
                    {
                        name: '4GB',
                        checked: false
                    },
                    {
                        name: '8GB',
                        checked: false
                    }, {
                        name: '16GB',
                        checked: false
                    },
                    {
                        name: '32GB',
                        checked: false
                    },
                ]
            },

            {
                bigColumn: false,
                notVisible: true,
                name: 'brands',
                type: 'Netwerk & Remote',
                option: [
                    {
                        name: 'HP',
                        checked: false
                    },
                    {
                        name: 'Dell',
                        checked: false
                    }, {
                        name: 'Intel',
                        checked: false
                    },
                    {
                        name: 'Broadcom',
                        checked: false
                    },
                    {
                        name: 'Anders',
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                notVisible: true,
                name: 'sub_parts',
                type: 'Netwerk & Remote',
                option: [
                    {
                        name: 'Dochter kaarten',
                        checked: false
                    },
                    {
                        name: 'Expansion kaarten',
                        checked: false
                    }, {
                        name: 'Raid / HBA',
                        checked: false
                    },
                    {
                        name: 'Riser kaarten',
                        checked: false
                    },
                    {
                        name: 'iDrac / iLO controllers',
                        checked: false
                    },
                ]
            },

            {
                bigColumn: false,
                notVisible: true,
                name: 'brands',
                type: 'Powersupply',
                option: [
                    {
                        name: 'HP',
                        checked: false
                    },
                    {
                        name: 'Dell',
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                notVisible: true,
                name: 'powersupply',
                type: 'Powersupply',
                option: [
                    {
                        name: '460W - 500W',
                        checked: false
                    },
                    {
                        name: '700W - 1100W',
                        checked: false
                    },
                    {
                        name: '1200W - 1400W',
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                notVisible: true,
                name: 'statusPower',
                type: 'Powersupply',
                option: [
                    {
                        name: 'Silver',
                        checked: false
                    },
                    {
                        name: 'Gold',
                        checked: false
                    },
                    {
                        name: 'Platinum',
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                notVisible: true,
                name: 'sub_power',
                type: 'Powersupply HP',
                option: [
                    {
                        name: 'Met flex slot unit',
                        checked: false
                    },
                ]
            },

            {
                bigColumn: false,
                notVisible: true,
                name: 'brands',
                type: 'Kabels & Adapters',
                option: [
                    {
                        name: 'HP',
                        checked: false
                    },
                    {
                        name: 'Dell',
                        checked: false
                    },
                    {
                        name: 'Anders',
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                notVisible: true,
                name: 'sub_kabels',
                type: 'Kabels & Adapters',
                option: [
                    {
                        name: 'GPU-kit',
                        checked: false
                    },
                    {
                        name: 'Data kabels',
                        checked: false
                    },
                    {
                        name: 'Netwerk kabels',
                        checked: false
                    },
                    {
                        name: 'Adapters',
                        checked: false
                    },
                ]
            },

            {
                bigColumn: false,
                notVisible: true,
                name: 'heatsinksFans',
                type: 'Heatsinks / Fans',
                option: [
                    {
                        name: 'Heatsinks',
                        checked: false
                    },
                    {
                        name: 'Fans',
                        checked: false
                    },
                ]
            },

            {
                bigColumn: false,
                notVisible: true,
                name: 'brands',
                type: 'Cage & Backplane',
                option: [
                    {
                        name: 'HP',
                        checked: false
                    },
                    {
                        name: 'Dell',
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                notVisible: true,
                name: 'cage_backplane',
                type: 'Cage & Backplane',
                option: [
                    {
                        name: 'Backplane',
                        checked: false
                    },
                    {
                        name: 'Cage',
                        checked: false
                    },
                ]
            },

            {
                bigColumn: false,
                name: 'brands',
                isRadio: true,
                notVisible: true,
                categoryId: 1,
                type: 'Moederbord & Barebone',
                option: [
                    {
                        name: 'Dell',
                        checked: false
                    },
                    {
                        name: 'HP',
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                name: 'version_type',
                type: 'Moederbord & Barebone',
                notVisible: true,
                option: []
            },

            {
                bigColumn: false,
                notVisible: true,
                name: 'brands',
                type: 'Rackrails',
                option: [
                    {
                        name: 'HP',
                        checked: false
                    },
                    {
                        name: 'Dell',
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                notVisible: true,
                name: 'type_rails',
                type: 'Rackrails',
                option: [
                    {
                        name: 'Rackrails',
                        checked: false
                    },
                    {
                        name: 'Cable managment arm',
                        checked: false
                    },
                    {
                        name: 'Conversion kit',
                        checked: false
                    },{
                        name: 'Ears',
                        checked: false
                    },
                ]
            },

            {
                bigColumn: false,
                notVisible: true,
                name: 'brands',
                type: 'CaddyTray',
                option: [
                    {
                        name: 'HP',
                        checked: false
                    },
                    {
                        name: 'Dell',
                        checked: false
                    },
                ]
            },
            {
                bigColumn: false,
                notVisible: true,
                name: 'type_sata',
                type: 'CaddyTray',
                option: [
                    {
                        name: 'SFF (2,5")',
                        checked: false
                    },
                    {
                        name: 'LFF (3,5")',
                        checked: false
                    },
                    {
                        name: 'Converter SFF / LFF',
                        checked: false
                    },
                ]
            },
        ]
    }
];
