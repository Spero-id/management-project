<?php

namespace App\Services;

use App\Models\Prospect;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProspectConversionService
{
    /**
     * Convert a prospect to a project with transaction safety
     *
     * @param Prospect $prospect
     * @param int $projectManagerId
     * @param string $projectName
     * @param string|null $description
     * @param bool $deleteProspect
     * @return Project
     * @throws \Exception
     */
    public function convertProspectToProject(
        Prospect $prospect,
        int $projectManagerId,
        string $projectName,
        ?string $description = null,
        bool $deleteProspect = false
    ): Project {
        return DB::transaction(function () use ($prospect, $projectManagerId, $projectName, $description, $deleteProspect) {
            // Validate that the project manager exists
            $projectManager = \App\Models\User::findOrFail($projectManagerId);

            // Create the project using the prospect's convertToProject method
            $project = $prospect->convertToProject(
                $projectManagerId,
                $projectName,
                $description,
                $deleteProspect
            );

            return $project;
        });
    }

    /**
     * Bulk convert multiple prospects to projects
     *
     * @param array $prospectData Array of prospect conversion data
     * @return array Array of created projects
     * @throws \Exception
     */
    public function bulkConvertProspects(array $prospectData): array
    {
        return DB::transaction(function () use ($prospectData) {
            $projects = [];

            foreach ($prospectData as $data) {
                $prospect = Prospect::findOrFail($data['prospect_id']);
                
                $project = $this->convertProspectToProject(
                    $prospect,
                    $data['project_manager_id'],
                    $data['project_name'],
                    $data['description'] ?? null,
                    $data['delete_prospect'] ?? false
                );

                $projects[] = $project;
            }

            return $projects;
        });
    }

    /**
     * Get prospects that are eligible for conversion to projects
     * (typically prospects with won or accepted status)
     *
     * @param array $eligibleStatusIds
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEligibleProspects(array $eligibleStatusIds = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = Prospect::with(['prospectStatus', 'preSalesPerson', 'quotations']);

        if (!empty($eligibleStatusIds)) {
            $query->whereIn('status_id', $eligibleStatusIds);
        }

        return $query->get();
    }

    /**
     * Validate prospect data before conversion
     *
     * @param Prospect $prospect
     * @param int $projectManagerId
     * @param string $projectName
     * @return array Validation errors (empty if valid)
     */
    public function validateConversion(Prospect $prospect, int $projectManagerId, string $projectName): array
    {
        $errors = [];

        // Check if project manager exists
        if (!\App\Models\User::where('id', $projectManagerId)->exists()) {
            $errors[] = 'Project manager not found';
        }

        // Check if project name is unique (optional business rule)
        if (Project::where('project_name', $projectName)->exists()) {
            $errors[] = 'Project name already exists';
        }

        // Check if prospect has required data
        if (empty($prospect->customer_name)) {
            $errors[] = 'Prospect must have a customer name';
        }

        if (empty($prospect->email)) {
            $errors[] = 'Prospect must have an email';
        }

        if (empty($prospect->company)) {
            $errors[] = 'Prospect must have a company';
        }

        return $errors;
    }
}