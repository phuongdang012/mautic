<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\LeadBundle\Entity;

use Mautic\CoreBundle\Entity\CommonRepository;

/**
 * Class LeadDeviceRepository.
 */
class LeadDeviceRepository extends CommonRepository
{
    /**
     * {@inhertidoc}.
     *
     * @param array $args
     *
     * @return Paginator
     */
    public function getEntities($args = [])
    {
        $q = $this
            ->createQueryBuilder('d')
            ->select('d');
        $args['qb'] = $q;

        return parent::getEntities($args);
    }

    /**
     * @param      $lead
     * @param null $deviceName
     * @param null $deviceBrand
     * @param null $deviceModel
     *
     * @return array
     */
    public function getDevice($lead, $deviceName = null, $deviceBrand = null, $deviceModel = null)
    {
        $sq = $this->_em->getConnection()->createQueryBuilder();
        $sq->select('es.id as id, es.device as device, es.device_fingerprint')
            ->from(MAUTIC_TABLE_PREFIX.'lead_devices', 'es');
        if (!empty($statIds)) {
            $inIds = (!is_array($statIds)) ? [(int) $statIds] : $statIds;

            $sq->where(
                $sq->expr()->in('es.id', $inIds)
            );
        }

        if ($deviceName !== null) {
            $sq->where(
                $sq->expr()->eq('es.device', ':device')
            )
                ->setParameter('device', $deviceName);
        }

        if ($deviceBrand !== null) {
            $sq->where(
                $sq->expr()->eq('es.device_brand', ':deviceBrand')
            )
                ->setParameter('deviceBrand', $deviceBrand);
        }

        if ($deviceModel !== null) {
            $sq->where(
                $sq->expr()->eq('es.device_model', ':deviceModel')
            )
                ->setParameter('deviceModel', $deviceModel);
        }

        if ($lead !== null) {
            $sq->where(
                $sq->expr()->eq('es.lead_id', $lead->getId())
            );
        }

        //get totals
        $device = $sq->execute()->fetchAll();

        return (!empty($device)) ? $device[0] : [];
    }
}
