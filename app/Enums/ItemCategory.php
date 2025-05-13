<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ItemCategory extends Enum
{
    const MedicalSupplies  = "Medical Supplies";
    const WoundCareSupplies = "Wound Care Supplies";    
    const MedicalEquipment = "Medical Equipment";
    const EnteralFeedingSupplies = "Enteral Feeding Supplies";
}
