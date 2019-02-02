<?php namespace ewma\dev\ui\node\controllers\main;

class Cp extends \Controller
{
    private $types = ['controller', 'js', 'less', 'template', 'session', 'storage'];

    public function reload()
    {
        $this->jquery('|')->replace($this->view());
    }

    public function view()
    {
        $v = $this->v('|');

        $this->smap('~|', 'module_path, node_path, type');

        $modulePath = $this->data['module_path'];
        $nodePath = $this->data['node_path'];
        $nodeType = $this->data('type');

        $svc = \ewma\dev\Svc::getInstance();

        $nodeFileAbsPath = $svc->getNodeFilePath($modulePath, $nodePath, $nodeType);

        $v->assign([
                       'PATH'     => '/' . $modulePath . ' ' . $nodePath,
                       'IDEA_URL' => "phpstorm://open/?file=" . $nodeFileAbsPath . '&line=1',
                       'NODE_URL' => abs_url('cp/modules/node/?path=' . $modulePath . ' ' . $nodePath . '&type=' . $nodeType),
                   ]);

        $nodeTypes = $svc->getNodeTypes($modulePath, $nodePath);

        $hasChanged = false;

        foreach ($this->types as $type) {
            $cache = $this->d('~:' . $svc->getCachePath($modulePath, $nodePath, $type));

            $class = 'tab ' . $type;
            if (in_array($type, $nodeTypes)) {
                $class .= ' has_file';
            }

            $lockedBy = false;
            if (isset($cache['user']) && $cache['user'] != $this->_user('id')) {
                $lockedBy = $cache['user'];
            }

            if (isset($cache['user']) && $cache['user'] == $this->_user('id')) {
                $hasChanged = true;
            }

            if ($this->data['type'] == $type) {
                $class .= ' selected';

                if ($cache) {
                    if ($lockedBy) {
                        if ($cacheOwner = \ewma\access\models\User::find($lockedBy)) {
                            $v->assign('locked_info', [
                                'USER' => $cacheOwner->login ? $cacheOwner->login : '#' . $cacheOwner->id
                            ]);
                        } else {
                            // release file
                        }
                    }

                    if (!$lockedBy) {
                        $v->assign('row_2_buttons/apply', [
                            'SAVE'  => $this->c('\std\ui button:view', [
                                'path'    => '>xhr:save|',
                                'data'    => [
                                    'type' => $this->data['type']
                                ],
                                'class'   => 'button',
                                'content' => 'save'
                            ]),
                            'RESET' => $this->c('\std\ui button:view', [
                                'ctrl'    => [
                                    'path' => '>xhr:reset|',
                                    'data' => [
                                        'type' => $this->data['type']
                                    ]
                                ],
                                'class'   => 'button',
                                'content' => 'reset'
                            ]),
                        ]);
                    }
                }

                if (!$lockedBy) {
                    $v->assign('row_2_buttons/delete', [
                        'BUTTON' => $this->c('\std\ui button:view', [
                            'visible' => file_exists($nodeFileAbsPath),
                            'ctrl'    => [
                                'path' => '>xhr:delete|',
                                'data' => [
                                    'type' => $this->data['type']
                                ]
                            ],
                            'class'   => 'button',
                            'content' => 'delete'
                        ]),
                    ]);
                }
            }

            $v->assign('tab', [
                'BUTTON' => $this->c('\std\ui button:view', [
                    'path'  => '>xhr:setType|',
                    'data'  => [
                        'type' => $type
                    ],
                    'class' => $class,
                    'label' => $type . ($cache ? '*' : ''),
                    'icon'  => $lockedBy ? 'fa fa-user' : ''
                ])
            ]);
        }

        if ($hasChanged) {
            $v->assign('row_1_buttons/save', [
                'BUTTON' => $this->c('\std\ui button:view', [
                    'path'    => '>xhr:saveAll|',
                    'data'    => [
                        'type' => $this->data['type']
                    ],
                    'class'   => 'button',
                    'content' => 'save'
                ])
            ]);
        }

        $v->assign('row_1_buttons/delete', [
            'BUTTON' => $this->c('\std\ui button:view', [
                'ctrl'    => [
                    'path' => '>xhr:deleteAll|',
                    'data' => [
                        'type' => $this->data['type']
                    ]
                ],
                'class'   => 'button',
                'content' => 'delete'
            ])
        ]);

        $this->css(':common');

        $this->se('ewma/dev/nodeEditor/update/' . $this->data('type') . '/' . $this->_instance())->rebind(':reload|');
        $this->se('ewma/dev/nodeEditor/save/' . $this->_instance())->rebind(':reload|');

        return $v;
    }

    public function hasOwnChangedTypes()
    {
        $cache = $this->d('~:cache');

        foreach ($cache as $md5 => $data) {
            if (null !== $data) {
                return true;
            }
        }

        return false;
    }
}
