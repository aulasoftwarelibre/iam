<?php

declare(strict_types=1);

/*
 * This file is part of the `iam` project.
 *
 * (c) Aula de Software Libre de la UCO <aulasoftwarelibre@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AulaSoftwareLibre\Iam\Infrastructure\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;

class DummyDataPersist implements DataPersisterInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports($data): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function persist($data)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data)
    {
    }
}
