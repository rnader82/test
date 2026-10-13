<div class="modal-header justify-content-end">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    @include('issues.form', [
        'issue' => $issue,
        'groups' => $groups,
        'sites' => $sites,
        'clients' => $clients ?? [],
        'origines' => $origines,
        'issues_groups' => $issues_groups,
        'users' => $users,
        'statuses' => $statuses,
        'states' => $states,
        'issueTypes' => $issueTypes ?? [],
        'issueCriticities' => $issueCriticities ?? [],
        'issueCategories' => $issueCategories ?? [],
        'action' => $action,
        'method' => $method
    ])
</div>