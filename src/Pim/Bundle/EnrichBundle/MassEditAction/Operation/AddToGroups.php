<?php

namespace Pim\Bundle\EnrichBundle\MassEditAction\Operation;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Adds many products to many groups
 *
 * @author    Gildas Quemener <gildas@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class AddToGroups extends AbstractMassEditOperation implements
    ConfigurableOperationInterface,
    BatchableOperationInterface
{
    /** @var ArrayCollection */
    protected $groups;

    /** @var string[] */
    protected $warningMessages;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    /**
     * Set groups
     *
     * @param ArrayCollection $groups
     *
     * @return $this
     */
    public function setGroups(ArrayCollection $groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Get groups
     *
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormOptions()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getFormType()
    {
        return 'pim_enrich_mass_add_to_groups';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'add-to-groups';
    }

    /**
     * {@inheritdoc}
     */
    public function getActions()
    {
        return [
            [
                'field' => 'groups',
                'value' => $this->getGroupsCode(),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getBatchConfig()
    {
        return addslashes(
            json_encode(
                [
                    'filters' => $this->getFilters(),
                    'actions' => $this->getActions(),
                ]
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBatchJobCode()
    {
        return 'update_product';
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsName()
    {
        return 'product';
    }

    /**
     * Get warning messages
     *
     * @return string[]
     */
    public function getWarningMessages()
    {
        if (null === $this->warningMessages) {
            $this->warningMessages = $this->generateWarningMessages();
        }

        return $this->warningMessages;
    }

    /**
     * @return array
     */
    protected function getGroupsCode()
    {
        $groupCodes = [];
        foreach ($this->getGroups() as $group) {
            $groupCodes[] = $group->getCode();
        }

        return $groupCodes;
    }

    /**
     * Get warning messages to display during the mass edit action
     *
     * @return string[]
     */
    protected function generateWarningMessages()
    {
        $messages = [];

        if (count($this->getGroupsCode()) === 0) {
            $messages[] = [
                'key'     => 'pim_enrich.mass_edit_action.add-to-groups.no_group',
                'options' => []
            ];
        }

        return $messages;
    }
}
