<x-app-layout>
<div class="container py-4">

    <h2 class="fw-semibold mb-4">System Settings</h2>

    <div class="card shadow-sm p-4">

        <p class="text-muted">
            In the future, we will configure system-wide parameters here.
        </p>

        <ul>
            <li>Default submission window</li>
            <li>14-day SLA configuration</li>
            <li>Email notification toggle</li>
            <li>System branding</li>
        </ul>

        <div class="alert alert-info mt-3">
            {{-- TABLE HERE --}}
            <style>
                .team-table-wrapper {
                    width: 100%;
                    overflow-x: auto;
                    margin-top: 0.5rem;
                }
                .team-table {
                    width: 100%;
                    border-collapse: collapse;
                    background: white;
                    border-radius: 0.75rem;
                    overflow: hidden;
                    font-size: 0.95rem;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
                }
                .team-table thead tr {
                    background: #1e2f4e;
                    color: white;
                }
                .team-table th {
                    font-weight: 600;
                    padding: 0.9rem 1.2rem;
                    text-align: left;
                }
                .team-table td {
                    padding: 0.8rem 1.2rem;
                    border-bottom: 1px solid #e9edf2;
                    vertical-align: top;
                }
                .team-table tbody tr:nth-child(even) td {
                    background-color: #fafcff;
                }
                .role-name {
                    font-weight: 650;
                    color: #0a2942;
                    white-space: nowrap;
                }
                .members-list {
                    line-height: 1.45;
                    color: #1f3b4c;
                }
                .contribution-text {
                    line-height: 1.45;
                    color: #2c3e4e;
                    font-size: 0.9rem;
                }
                .table-heading {
                    font-size: 1.1rem;
                    font-weight: 600;
                    margin-bottom: 0.75rem;
                    color: #0a2942;
                    border-left: 4px solid #1e2f4e;
                    padding-left: 0.75rem;
                }
                @media (max-width: 640px) {
                    .team-table th, .team-table td {
                        padding: 0.7rem 0.9rem;
                        font-size: 0.85rem;
                    }
                    .contribution-text {
                        font-size: 0.8rem;
                    }
                    .role-name {
                        white-space: normal;
                    }
                }
            </style>
            <div class="table-heading">📋 Team Roles & Responsibilities</div>
            <div class="team-table-wrapper">
                <table class="team-table">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Assigned Member(s)</th>
                            <th>Contribution</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Scrum Master -->
                        <tr>
                            <td class="role-name">Scrum Master</td>
                            <td><div class="members-list">Phillip</div></td>
                            <td class="contribution-text">Facilitated Agile ceremonies, removed impediments, coached the team on Scrum practices, and ensured smooth sprint execution.</td>
                        </tr>
                        <!-- Programmers -->
                        <tr>
                            <td class="role-name">Programmers</td>
                            <td><div class="members-list">Faraha, Tapasvini, Phillip</div></td>
                            <td class="contribution-text">Developed and implemented features, wrote clean and maintainable code, conducted peer reviews, and ensured technical deliverables meet acceptance criteria.</td>
                        </tr>
                        <!-- Database Designers -->
                        <tr>
                            <td class="role-name">Database Designers</td>
                            <td><div class="members-list">Joel, Abraham, Faraha</div></td>
                            <td class="contribution-text">Designed optimized database schemas, defined relationships, ensured data integrity, and supported query performance for application needs.</td>
                        </tr>
                        <!-- Web Designers -->
                        <tr>
                            <td class="role-name">Web Designers</td>
                            <td><div class="members-list">Sadja, Tapasvini, Sophia</div></td>
                            <td class="contribution-text">Created responsive UI/UX designs, implemented front-end components, ensured accessibility standards, and maintained visual consistency.</td>
                        </tr>
                        <!-- Testers -->
                        <tr>
                            <td class="role-name">Testers</td>
                            <td><div class="members-list">Abraham, Sadja, Philip</div></td>
                            <td class="contribution-text">Developed test plans, executed manual and automated tests, reported bugs, verified fixes, and ensured overall system quality and reliability.</td>
                        </tr>
                        <!-- Information Architects -->
                        <tr>
                            <td class="role-name">Information Architects</td>
                            <td><div class="members-list">Ken, Abraham, Sophia</div></td>
                            <td class="contribution-text">Structured the information flow, designed navigation systems, organized content hierarchy, and optimized user findability across the platform.</td>
                        </tr>
                        <!-- Product Owner -->
                        <tr>
                            <td class="role-name">Product Owner</td>
                            <td><div class="members-list">Tr. Silas</div></td>
                            <td class="contribution-text">Define and prioritize product backlog, clarify requirements, align stakeholders, and validate that delivered features meet business value.</td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
</x-app-layout>