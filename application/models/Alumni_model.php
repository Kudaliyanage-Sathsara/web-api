<?php
class Alumni_model extends CI_Model {

    public function get_alumni($filters = [])
    {
        $this->db->select('u.id, u.email, ai.programme, ai.graduation_year, ai.industry_sector, ai.current_location, upi.full_name, upi.profile_image_url');
        $this->db->from('users u');
        $this->db->join('alumni_info ai', 'u.id = ai.user_id', 'left');
        $this->db->join('user_personal_infos upi', 'u.id = upi.user_id', 'left');
        $this->db->where('u.is_verified', 1);

        if (!empty($filters['programme'])) {
            $this->db->where('ai.programme', $filters['programme']);
        }
        if (!empty($filters['graduation_year'])) {
            $this->db->where('ai.graduation_year', $filters['graduation_year']);
        }
        if (!empty($filters['industry_sector'])) {
            $this->db->where('ai.industry_sector', $filters['industry_sector']);
        }

        return $this->db->get()->result();
    }

    public function get_alumni_stats()
    {
        // Total users count
        $total_users = $this->db->select('COUNT(*) as count')
            ->from('users')
            ->where('is_verified', 1)
            ->get()->row()->count;

        // Count by programme (from user_degrees table - distinct degrees)
        $programme_stats = $this->db->select('degree as programme, COUNT(DISTINCT u.id) as count')
            ->from('user_degrees ud')
            ->join('users u', 'ud.user_id = u.id', 'inner')
            ->where('u.is_verified', 1)
            ->group_by('ud.degree')
            ->get()->result();

        // Count by graduation year
        $year_stats = $this->db->select('graduation_year, COUNT(*) as count')
            ->from('alumni_info')
            ->group_by('graduation_year')
            ->order_by('graduation_year')
            ->get()->result();

        // Count by industry sector
        $sector_stats = $this->db->select('industry_sector, COUNT(*) as count')
            ->from('alumni_info')
            ->group_by('industry_sector')
            ->get()->result();

        // Count by current location
        $location_stats = $this->db->select('current_location, COUNT(*) as count')
            ->from('alumni_info')
            ->where('current_location IS NOT NULL AND current_location != ""')
            ->group_by('current_location')
            ->order_by('count', 'DESC')
            ->limit(10)
            ->get()->result();

        // Employment status distribution
        $employment_stats = $this->db->select('
                CASE
                    WHEN end_date IS NULL OR end_date = "" THEN "Currently Employed"
                    WHEN end_date < CURDATE() THEN "Previously Employed"
                    ELSE "Currently Employed"
                END as status,
                COUNT(*) as count
            ')
            ->from('employment_history')
            ->group_by('status')
            ->get()->result();

        // Degrees distribution
        $degree_stats = $this->db->select('degree, COUNT(*) as count')
            ->from('degrees')
            ->group_by('degree')
            ->order_by('count', 'DESC')
            ->limit(10)
            ->get()->result();

        // Certifications by provider
        $certification_stats = $this->db->select('provider, COUNT(*) as count')
            ->from('certifications')
            ->where('provider IS NOT NULL AND provider != ""')
            ->group_by('provider')
            ->order_by('count', 'DESC')
            ->limit(8)
            ->get()->result();

        // Skills/competencies from employment descriptions (simplified)
        $skills_stats = $this->db->select('
                CASE
                    WHEN LOWER(description) LIKE "%leadership%" THEN "Leadership"
                    WHEN LOWER(description) LIKE "%management%" THEN "Management"
                    WHEN LOWER(description) LIKE "%development%" OR LOWER(description) LIKE "%programming%" THEN "Technical Development"
                    WHEN LOWER(description) LIKE "%analysis%" OR LOWER(description) LIKE "%analytics%" THEN "Data Analysis"
                    WHEN LOWER(description) LIKE "%research%" THEN "Research"
                    WHEN LOWER(description) LIKE "%teaching%" OR LOWER(description) LIKE "%education%" THEN "Education"
                    ELSE "Other Professional Skills"
                END as skill_category,
                COUNT(*) as count
            ')
            ->from('employment_history')
            ->where('description IS NOT NULL AND description != ""')
            ->group_by('skill_category')
            ->order_by('count', 'DESC')
            ->get()->result();

        // Monthly registration trends (last 12 months)
        $registration_trends = $this->db->select('
                DATE_FORMAT(created_at, "%Y-%m") as month,
                COUNT(*) as count
            ')
            ->from('users')
            ->where('created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)')
            ->group_by('month')
            ->order_by('month')
            ->get()->result();

        // Profile completion rates
        $profile_completion = $this->db->select('
                ROUND(AVG(
                    (CASE WHEN ai.programme IS NOT NULL THEN 1 ELSE 0 END +
                     CASE WHEN ai.graduation_year IS NOT NULL THEN 1 ELSE 0 END +
                     CASE WHEN ai.industry_sector IS NOT NULL THEN 1 ELSE 0 END +
                     CASE WHEN ai.current_location IS NOT NULL THEN 1 ELSE 0 END +
                     CASE WHEN ai.biography IS NOT NULL THEN 1 ELSE 0 END) / 5.0 * 100
                )) as completion_rate
            ')
            ->from('users u')
            ->join('alumni_info ai', 'u.id = ai.user_id', 'left')
            ->get()->row();

        // Institution distribution
        $institution_stats = $this->db->select('institution, COUNT(*) as count')
            ->from('user_degrees')
            ->group_by('institution')
            ->order_by('count', 'DESC')
            ->get()->result();

        // Employment duration analysis (mock data for now - would need more complex query)
        $employment_duration_stats = [
            (object)['duration' => '< 1 year', 'count' => 2],
            (object)['duration' => '1-2 years', 'count' => 4],
            (object)['duration' => '2-5 years', 'count' => 8],
            (object)['duration' => '5-10 years', 'count' => 6],
            (object)['duration' => '10+ years', 'count' => 5]
        ];

        // Certification trends (mock data - would need date-based analysis)
        $certification_trends = [
            (object)['month' => '2022-01', 'count' => 2],
            (object)['month' => '2022-02', 'count' => 1],
            (object)['month' => '2022-03', 'count' => 3],
            (object)['month' => '2022-04', 'count' => 2],
            (object)['month' => '2022-05', 'count' => 4],
            (object)['month' => '2022-06', 'count' => 3],
            (object)['month' => '2022-07', 'count' => 5],
            (object)['month' => '2022-08', 'count' => 4],
            (object)['month' => '2022-09', 'count' => 6],
            (object)['month' => '2022-10', 'count' => 5],
            (object)['month' => '2022-11', 'count' => 7],
            (object)['month' => '2022-12', 'count' => 6]
        ];

        // Profile completion breakdown
        $profile_completion_breakdown = $this->db->select('
                CASE
                    WHEN completion_rate >= 80 THEN "Complete"
                    WHEN completion_rate >= 60 THEN "Mostly Complete"
                    WHEN completion_rate >= 30 THEN "Partial"
                    ELSE "Minimal"
                END as completion_level,
                COUNT(*) as count
            ')
            ->from('(
                SELECT u.id,
                    ROUND(AVG(
                        (CASE WHEN ai.programme IS NOT NULL THEN 1 ELSE 0 END +
                         CASE WHEN ai.graduation_year IS NOT NULL THEN 1 ELSE 0 END +
                         CASE WHEN ai.industry_sector IS NOT NULL THEN 1 ELSE 0 END +
                         CASE WHEN ai.current_location IS NOT NULL THEN 1 ELSE 0 END +
                         CASE WHEN upi.biography IS NOT NULL THEN 1 ELSE 0 END) / 5.0 * 100
                    )) as completion_rate
                FROM users u
                LEFT JOIN alumni_info ai ON u.id = ai.user_id
                LEFT JOIN user_personal_infos upi ON u.id = upi.user_id
                GROUP BY u.id
            ) as completion_rates')
            ->group_by('completion_level')
            ->get()->result();

        return [
            'total_users' => $total_users,
            'programme' => $programme_stats,
            'graduation_year' => $year_stats,
            'industry_sector' => $sector_stats,
            'current_location' => $location_stats,
            'employment_status' => $employment_stats,
            'degrees' => $degree_stats,
            'certifications' => $certification_stats,
            'skills' => $skills_stats,
            'registration_trends' => $registration_trends,
            'profile_completion' => $profile_completion,
            'institutions' => $institution_stats,
            'employment_duration' => $employment_duration_stats,
            'certification_trends' => $certification_trends,
            'profile_completion_breakdown' => $profile_completion_breakdown
        ];
    }

    public function save_alumni_info($user_id, $data)
    {
        $exists = $this->db->get_where('alumni_info', ['user_id' => $user_id])->row();
        if ($exists) {
            $this->db->where('user_id', $user_id);
            return $this->db->update('alumni_info', $data);
        }
        $data['user_id'] = $user_id;
        return $this->db->insert('alumni_info', $data);
    }

    public function get_analysis_data()
    {
        // User Statistics
        $total_users = $this->db->select('COUNT(*) as count')->from('users')->get()->row()->count;
        $verified_users = $this->db->select('COUNT(*) as count')->from('users')->where('is_verified', 1)->get()->row()->count;
        
        // User Profile Data
        $users_with_personal_info = $this->db->select('COUNT(DISTINCT user_id) as count')->from('user_personal_infos')->get()->row()->count;
        $users_with_degrees = $this->db->select('COUNT(DISTINCT user_id) as count')->from('user_degrees')->get()->row()->count;
        $users_with_employment = $this->db->select('COUNT(DISTINCT user_id) as count')->from('user_employment_history')->get()->row()->count;
        $users_with_certifications = $this->db->select('COUNT(DISTINCT user_id) as count')->from('user_certifications')->get()->row()->count;

        // Degrees Analysis
        $degrees_by_type = $this->db->select('degree, COUNT(*) as count')
            ->from('user_degrees')
            ->group_by('degree')
            ->order_by('count', 'DESC')
            ->get()->result();

        $institutions = $this->db->select('institution, COUNT(DISTINCT user_id) as count')
            ->from('user_degrees')
            ->group_by('institution')
            ->order_by('count', 'DESC')
            ->limit(15)
            ->get()->result();

        // Employment Analysis
        $employment_by_role = $this->db->select('role, COUNT(*) as count')
            ->from('user_employment_history')
            ->group_by('role')
            ->order_by('count', 'DESC')
            ->limit(15)
            ->get()->result();

        $employment_by_company = $this->db->select('company, COUNT(DISTINCT user_id) as count')
            ->from('user_employment_history')
            ->group_by('company')
            ->order_by('count', 'DESC')
            ->limit(15)
            ->get()->result();

        // Currently Employed Analysis
        $currently_employed = $this->db->select('COUNT(*) as count')
            ->from('user_employment_history')
            ->where('end_date IS NULL')
            ->get()->row()->count;

        // Certifications Analysis
        $certifications_by_provider = $this->db->select('provider, COUNT(*) as count')
            ->from('user_certifications')
            ->where('provider IS NOT NULL')
            ->group_by('provider')
            ->order_by('count', 'DESC')
            ->limit(15)
            ->get()->result();

        // LinkedIn Profiles
        $users_with_linkedin = $this->db->select('COUNT(DISTINCT user_id) as count')->from('user_linkedin_profiles')->get()->row()->count;

        // Licenses
        $total_licenses = $this->db->select('COUNT(*) as count')->from('user_licenses')->get()->row()->count;

        // Short Courses
        $total_short_courses = $this->db->select('COUNT(*) as count')->from('user_short_courses')->get()->row()->count;

        // Latest registrations
        $latest_registrations = $this->db->select('email, created_at')
            ->from('users')
            ->order_by('created_at', 'DESC')
            ->limit(10)
            ->get()->result();

        // Registration by Month
        $registration_by_month = $this->db->select('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->from('users')
            ->group_by('month')
            ->order_by('month', 'DESC')
            ->limit(12)
            ->get()->result();

        return [
            'user_stats' => [
                'total_users' => $total_users,
                'verified_users' => $verified_users,
                'unverified_users' => $total_users - $verified_users,
                'users_with_personal_info' => $users_with_personal_info,
                'users_with_degrees' => $users_with_degrees,
                'users_with_employment' => $users_with_employment,
                'users_with_certifications' => $users_with_certifications,
                'users_with_linkedin' => $users_with_linkedin
            ],
            'degrees_data' => [
                'by_type' => $degrees_by_type,
                'institutions' => $institutions
            ],
            'employment_data' => [
                'by_role' => $employment_by_role,
                'by_company' => $employment_by_company,
                'currently_employed' => $currently_employed
            ],
            'certifications_data' => [
                'by_provider' => $certifications_by_provider,
                'total_licenses' => $total_licenses,
                'total_short_courses' => $total_short_courses
            ],
            'trends' => [
                'registration_by_month' => array_reverse($registration_by_month),
                'latest_registrations' => $latest_registrations
            ]
        ];
    }
}