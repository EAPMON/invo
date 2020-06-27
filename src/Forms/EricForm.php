<?php
declare(strict_types=1);

/**
 * This file is part of the Invo.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Invo\Forms;

use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;

class EricForm extends Form
{
    /**
     * Initialize the products form
     *
     * @param null $entity
     * @param array $options
     */
    public function initialize($entity = null, array $options = [])
    {
        if (!isset($options['edit'])) {
            $this->add((new Text('id'))->setLabel('Id'));
        } else {
            $this->add(new Hidden('id'));
        }

        /**
         * Name text field
         */
        $id = new Text('id');
        $id->setLabel('Id');
        $id->setFilters(['striptags', 'string']);
        $id->addValidators([
            new PresenceOf(['message' => 'id is required']),
        ]);

        $this->add($id);

        $description = new Text('description');
        $description->setLabel('Description');
        $description->setFilters(['striptags', 'string']);
        $description->addValidators([
            new PresenceOf(['message' => 'description is required']),
        ]);

        $this->add($description);

        $price = new Text('price');
        $price->setLabel('Price');
        $price->setFilters(['float']);
        $price->addValidators([
            new PresenceOf(['message' => 'Price is required']),
            new Numericality(['message' => 'Price is required']),
        ]);

        $this->add($price);
    }
}
