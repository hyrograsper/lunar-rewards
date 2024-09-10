<?php

return [
    'plural_label' => 'Rewards',
    'label' => 'Reward',
    'form' => [
        'conditions' => [
            'heading' => 'Conditions',
        ],
        'buy_x_earn_y' => [
            'heading' => 'Buy X Earn Y',
            'min_qty' => [
                'label' => 'Product Quantity',
                'helper_text' => 'Set how many qualifying products are required for the reward to apply.',
            ],
            'reward_qty' => [
                'label' => 'No. of rewards',
                'helper_text' => 'How many reward points to earn per number of qualified products purchased.',
            ],
        ],
        'spend_x_earn_y' => [
            'heading' => 'Spend X Earn Y',
            'min_qty' => [
                'label' => 'Spend Quantity',
                'helper_text' => 'Set how much spend of qualifying products are required for the reward to apply.',
            ],
            'reward_qty' => [
                'label' => 'No. of rewards',
                'helper_text' => 'How many reward points to earn per amount spent on qualifying products.',
            ],
        ],
        'fixed_amount' => [
            'heading' => 'Fixed Amount',
            'reward_qty' => [
                'label' => 'Points',
            ],
        ],
        'name' => [
            'label' => 'Name',
        ],
        'handle' => [
            'label' => 'Handle',
        ],
        'starts_at' => [
            'label' => 'Start Date',
        ],
        'ends_at' => [
            'label' => 'End Date',
        ],
        'priority' => [
            'label' => 'Priority',
            'helper_text' => 'Rewards with higher priority will be applied first.',
            'options' => [
                'low' => [
                    'label' => 'Low',
                ],
                'medium' => [
                    'label' => 'Medium',
                ],
                'high' => [
                    'label' => 'High',
                ],
            ],
        ],
        'stop' => [
            'label' => 'Stop other rewards applying after this one',
        ],
        'coupon' => [
            'label' => 'Coupon',
            'helper_text' => 'Enter the coupon required for the reward to apply, if left blank it will apply automatically.',
        ],
        'max_uses' => [
            'label' => 'Max uses',
            'helper_text' => 'Leave blank for unlimited uses.',
        ],
        'max_uses_per_user' => [
            'label' => 'Max uses per user',
            'helper_text' => 'Leave blank for unlimited uses.',
        ],
        'minimum_cart_amount' => [
            'label' => 'Minimum Cart Amount',
        ],
        'min_qty' => [
            'label' => 'Product Quantity',
            'helper_text' => 'Set how many qualifying products are required for the reward to apply.',
        ],
        'reward_qty' => [
            'label' => 'No. of free items',
            'helper_text' => 'How many of each item are rewarded.',
        ],
        'max_reward_qty' => [
            'label' => 'Maximum reward quantity',
            'helper_text' => 'The maximum amount of products which can be rewarded, regardless of criteria.',
        ],
        'automatic_rewards' => [
            'label' => 'Automatically add rewards',
            'helper_text' => 'Switch on to add reward products when not present in the basket.',
        ],
    ],
    'table' => [
        'name' => [
            'label' => 'Name',
        ],
        'status' => [
            'label' => 'Status',
            \Hyrograsper\LunarRewards\Models\Reward::ACTIVE => [
                'label' => 'Active',
            ],
            \Hyrograsper\LunarRewards\Models\Reward::PENDING => [
                'label' => 'Pending',
            ],
            \Hyrograsper\LunarRewards\Models\Reward::EXPIRED => [
                'label' => 'Expired',
            ],
            \Hyrograsper\LunarRewards\Models\Reward::SCHEDULED => [
                'label' => 'Scheduled',
            ],
        ],
        'type' => [
            'label' => 'Type',
        ],
        'starts_at' => [
            'label' => 'Start Date',
        ],
        'ends_at' => [
            'label' => 'End Date',
        ],
    ],
    'pages' => [
        'availability' => [
            'label' => 'Availability',
        ],
        'limitations' => [
            'label' => 'Limitations',
        ],
    ],
    'relationmanagers' => [
        'collections' => [
            'title' => 'Collections',
            'description' => 'Select which collections this reward should be limited to.',
            'actions' => [
                'attach' => [
                    'label' => 'Attach Collection',
                ],
            ],
            'table' => [
                'name' => [
                    'label' => 'Name',
                ],
                'type' => [
                    'label' => 'Type',
                    'limitation' => [
                        'label' => 'Limitation',
                    ],
                    'exclusion' => [
                        'label' => 'Exclusion',
                    ],
                ],
            ],
            'form' => [
                'type' => [
                    'options' => [
                        'limitation' => [
                            'label' => 'Limitation',
                        ],
                        'exclusion' => [
                            'label' => 'Exclusion',
                        ],
                    ],
                ],
            ],
        ],
        'brands' => [
            'title' => 'Brands',
            'description' => 'Select which brands this reward should be limited to.',
            'actions' => [
                'attach' => [
                    'label' => 'Attach Brand',
                ],
            ],
            'table' => [
                'name' => [
                    'label' => 'Name',
                ],
                'type' => [
                    'label' => 'Type',
                    'limitation' => [
                        'label' => 'Limitation',
                    ],
                    'exclusion' => [
                        'label' => 'Exclusion',
                    ],
                ],
            ],
            'form' => [
                'type' => [
                    'options' => [
                        'limitation' => [
                            'label' => 'Limitation',
                        ],
                        'exclusion' => [
                            'label' => 'Exclusion',
                        ],
                    ],
                ],
            ],
        ],
        'products' => [
            'title' => 'Products',
            'description' => 'Select which products this reward should be limited to.',
            'actions' => [
                'attach' => [
                    'label' => 'Add Product',
                ],
            ],
            'table' => [
                'name' => [
                    'label' => 'Name',
                ],
                'type' => [
                    'label' => 'Type',
                    'limitation' => [
                        'label' => 'Limitation',
                    ],
                    'exclusion' => [
                        'label' => 'Exclusion',
                    ],
                ],
            ],
            'form' => [
                'type' => [
                    'options' => [
                        'limitation' => [
                            'label' => 'Limitation',
                        ],
                        'exclusion' => [
                            'label' => 'Exclusion',
                        ],
                    ],
                ],
            ],
        ],
        'rewards' => [
            'title' => 'Product Rewards',
            'description' => 'Select which products will be rewarded if they exist in the cart and the above conditions are met.',
            'actions' => [
                'attach' => [
                    'label' => 'Add Product',
                ],
            ],
            'table' => [
                'name' => [
                    'label' => 'Name',
                ],
                'type' => [
                    'label' => 'Type',
                    'limitation' => [
                        'label' => 'Limitation',
                    ],
                    'exclusion' => [
                        'label' => 'Exclusion',
                    ],
                ],
            ],
            'form' => [
                'type' => [
                    'options' => [
                        'limitation' => [
                            'label' => 'Limitation',
                        ],
                        'exclusion' => [
                            'label' => 'Exclusion',
                        ],
                    ],
                ],
            ],
        ],
        'conditions' => [
            'title' => 'Product Conditions',
            'description' => 'Select the products required for the reward to apply.',
            'actions' => [
                'attach' => [
                    'label' => 'Add Product',
                ],
            ],
            'table' => [
                'name' => [
                    'label' => 'Name',
                ],
                'type' => [
                    'label' => 'Type',
                    'limitation' => [
                        'label' => 'Limitation',
                    ],
                    'exclusion' => [
                        'label' => 'Exclusion',
                    ],
                ],
            ],
            'form' => [
                'type' => [
                    'options' => [
                        'limitation' => [
                            'label' => 'Limitation',
                        ],
                        'exclusion' => [
                            'label' => 'Exclusion',
                        ],
                    ],
                ],
            ],
        ],
        'productvariants' => [
            'title' => 'Product Variants',
            'description' => 'Select which product variants this reward should be limited to.',
            'actions' => [
                'attach' => [
                    'label' => 'Add Product Variant',
                ],
            ],
            'table' => [
                'name' => [
                    'label' => 'Name',
                ],
                'sku' => [
                    'label' => 'SKU',
                ],
                'values' => [
                    'label' => 'Option(s)',
                ],
            ],
            'form' => [
                'type' => [
                    'options' => [
                        'limitation' => [
                            'label' => 'Limitation',
                        ],
                        'exclusion' => [
                            'label' => 'Exclusion',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
