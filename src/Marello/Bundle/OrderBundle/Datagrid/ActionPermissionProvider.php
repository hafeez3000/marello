<?php

namespace Marello\Bundle\OrderBundle\Datagrid;

use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\DataGridBundle\Datasource\ResultRecordInterface;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowStep;
use Marello\Bundle\ReturnBundle\Entity\ReturnEntity;

class ActionPermissionProvider
{
    protected $excludedRefundableSteps = [
        'payment_reminder',
        'pending'
    ];

    protected $allowedReturnSteps = [
        'credit',
        'shipped',
        'complete'
    ];

    /** @var ObjectManager */
    protected $objectManager;

    /**
     * @param ObjectManager      $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param ResultRecordInterface $record
     * @return array
     */
    public function getOrderActionPermissions(ResultRecordInterface $record)
    {
        return array(
            'return'    => $this->isReturnApplicable($record),
            'refund'    => $this->isRefundApplicable($record->getValue('workflowStep')),
            'view'      => true,
            'delete'    => false
        );
    }

    /**
     * {@inheritdoc}
     * @param WorkflowStep $step
     * @return bool
     */
    protected function isRefundApplicable(WorkflowStep $step)
    {
        return (!in_array($step->getName(), $this->excludedRefundableSteps));
    }

    /**
     * {@inheritdoc}
     * @param $record
     * @return bool
     */
    protected function isReturnApplicable($record)
    {
        // workflow step allowed

        $workflowStep = $record->getValue('workflowStep');
        $isAllowedInWorkflow = (in_array($workflowStep->getName(), $this->allowedReturnSteps));

        if ($isAllowedInWorkflow) {
            return true;
        }
    }
}
