<?php

namespace Database\Factories;

use App\Models\DepartmentInfo;
use App\Models\SigEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentInfoFactory extends Factory
{
    protected $model = DepartmentInfo::class;

    public function definition(): array
    {
        return [
            'sig_event_id' => SigEvent::factory(),
            'user_role_id' => UserRoleFactory::new(),
            'additional_info' => $this->faker->paragraph(),
        ];
    }
}
