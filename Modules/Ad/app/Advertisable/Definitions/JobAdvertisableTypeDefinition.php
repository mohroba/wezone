<?php

namespace Modules\Ad\Advertisable\Definitions;

use Modules\Ad\Advertisable\DTO\AdvertisablePropertyDefinition;
use Modules\Ad\Models\AdJob;

final class JobAdvertisableTypeDefinition extends AbstractAdvertisableTypeDefinition
{
    public function key(): string
    {
        return 'job';
    }

    public function label(): string
    {
        return 'Job';
    }

    public function modelClass(): string
    {
        return AdJob::class;
    }

    public function description(): ?string
    {
        return 'Employment opportunities across industries and experience levels.';
    }

    /**
     * @return array<int, AdvertisablePropertyDefinition>
     */
    protected function defineBaseProperties(): array
    {
        return [
            new AdvertisablePropertyDefinition('slug', 'string', 'Slug', 'Unique, URL-friendly identifier for the job listing.', true, ['string', 'max:255']),
            new AdvertisablePropertyDefinition('company_name', 'string', 'Company Name', 'Hiring organisation or employer.'),
            new AdvertisablePropertyDefinition('position_title', 'string', 'Position Title', 'Role or job title being advertised.'),
            new AdvertisablePropertyDefinition('industry', 'string', 'Industry', 'Industry classification for the role.'),
            new AdvertisablePropertyDefinition('employment_type', 'string', 'Employment Type', 'Engagement type such as full-time or contract.'),
            new AdvertisablePropertyDefinition('experience_level', 'string', 'Experience Level', 'Required seniority level such as junior or senior.'),
            new AdvertisablePropertyDefinition('education_level', 'string', 'Education Level', 'Preferred education background.'),
            new AdvertisablePropertyDefinition('salary_min', 'integer', 'Minimum Salary', 'Lower bound for the salary range.'),
            new AdvertisablePropertyDefinition('salary_max', 'integer', 'Maximum Salary', 'Upper bound for the salary range.'),
            new AdvertisablePropertyDefinition('currency', 'string', 'Currency', 'Currency code for the salary range.'),
            new AdvertisablePropertyDefinition('salary_type', 'string', 'Salary Type', 'Payment cadence such as monthly or hourly.'),
            new AdvertisablePropertyDefinition('work_schedule', 'string', 'Work Schedule', 'Work hours or shift expectations.'),
            new AdvertisablePropertyDefinition('remote_level', 'string', 'Remote Level', 'Remote work allowance, e.g. onsite or hybrid.'),
            new AdvertisablePropertyDefinition('benefits_json', 'json', 'Benefits', 'Structured list of job benefits or perks.'),
        ];
    }
}
