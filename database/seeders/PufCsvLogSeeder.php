<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PufCsvLogSeeder extends Seeder
{
    public function run(): void
    {
        $affiliations = [
            'National Government Agencies',
            'Local Government Units',
            'Academe',
            'Industry / Private Sector',
        ];

        $purposes = [
            'Service Laboratory',
            'Library Services',
            'Resource Speaker Request',
            'Food and Nutrition Trainings',
            'Sensory Evaluation',
            'Nutrition Counseling',
            'Technology Transfer Services',
            'FNRI Publications / IEC',
            'Conduct of R&D',
            'Processing Job Applications',
            'iFNRI',
        ];

        $visitorTypes = ['Individual', 'Group'];
        $visitTypes = ['walk-in', 'appointment'];
        $sexOptions = ['Male', 'Female'];

        $pufCsvIds = DB::table('puf_csv')->pluck('id')->toArray();

        if (empty($pufCsvIds)) {
            $this->command->warn('No puf_csv records found. Using ID 1 as default.');
            $pufCsvIds = [1];
        }

        $records = [];

        for ($i = 0; $i < 50; $i++) {
            $nps = rand(0, 10);
            $daysAgo = rand(0, 29);
            $downloadedAt = Carbon::now()->subDays($daysAgo)->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            $selectedPurposes = array_slice(
                $purposes,
                rand(0, count($purposes) - 3),
                rand(1, 3)
            );

            $ratings = [];
            foreach (['responsiveness', 'reliability', 'access', 'communication', 'costs', 'integrity', 'assurance', 'outcome', 'overall'] as $key) {
                $ratings[$key] = (string) rand(3, 5);
            }

            $csf_data = [
                'name'              => fake()->name(),
                'age'               => (string) rand(18, 65),
                'address'           => fake()->city() . ', ' . fake()->state(),
                'contact'           => fake()->phoneNumber(),
                'sex'               => $sexOptions[array_rand($sexOptions)],
                'other'             => [],
                'date'              => $downloadedAt->format('Y-m-d'),
                'visitType'         => $visitTypes[array_rand($visitTypes)],
                'apptTime'          => '',
                'servedTime'        => '',
                'visitorType'       => $visitorTypes[array_rand($visitorTypes)],
                'affiliation'       => $affiliations[array_rand($affiliations)],
                'affiliationOther'  => '',
                'purpose'           => $selectedPurposes,
                'purposeOther'      => '',
                'ratings'           => $ratings,
                'reasons'           => [],
                'cc1'               => 'Yes, aware before my transaction with this office',
                'cc2'               => 'Yes, the CC was easy to find',
                'cc3'               => 'Yes',
                'cc3Reason'         => '',
                'firstTime'         => rand(0, 1) ? 'Yes' : 'No',
                'learnedFrom'       => ['Website', 'Social Media'],
                'learnedFromOther'  => '',
                'nps'               => $nps,
                'comments'          => fake()->sentence(),
                'signCustomer'      => fake()->name(),
                'signContact'       => fake()->phoneNumber(),
                'consent'           => true,
            ];

            $records[] = [
                'puf_csv_id'    => $pufCsvIds[array_rand($pufCsvIds)],
                'post_id'       => rand(740, 760),
                'type'          => 'csv',
                'first_name'    => fake()->firstName(),
                'middle_name'   => '',
                'last_name'     => fake()->lastName(),   // ← Add this
                'gender'        => $sexOptions[array_rand($sexOptions)],
                'email'         => fake()->safeEmail(),
                'phone'         => fake()->phoneNumber(),
                'institution'   => $affiliations[array_rand($affiliations)],
                'address'       => fake()->city(),
                'industry_name' => '',
                'purpose'       => implode(', ', $selectedPurposes),
                'downloaded_at' => $downloadedAt,
                'csf_data'      => json_encode($csf_data),
                'created_at'    => $downloadedAt,
                'updated_at'    => $downloadedAt,
            ];
        }

        DB::table('puf_csv_logs')->insert($records);

        $this->command->info('Created 50 mock puf_csv_logs records!');
    }
}
