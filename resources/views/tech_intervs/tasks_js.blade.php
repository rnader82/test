@php
    $techIntervId = $techIntervId ?? null;
@endphp
<script>
(() => {
    window.techIntervId = window.techIntervId || @json($techIntervId);
    const taskInput = document.getElementById('newTask');
    const addTaskBtn = document.getElementById('addTask');

    const addTask = () => {
        const task = taskInput.value.trim();
        const id = window.techIntervId;
        if (!task || !id) return;

        fetch(`/tech_intervs/${id}/tasks`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ task })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const list = document.getElementById('taskList');
                const item = document.createElement('li');
                item.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
                item.dataset.taskId = data.task_id;
                item.innerHTML = `
                    <div class="form-check">
                        <input class="form-check-input toggle-task" type="checkbox" data-id="${data.task_id}">
                        <label class="form-check-label">${data.task}</label>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger delete-task" data-id="${data.task_id}">
                        <i class="bi bi-trash"></i>
                    </button>
                `;
                list.appendChild(item);
                taskInput.value = '';
                document.dispatchEvent(new Event('tasks-changed'));
            }
        });
    };

    addTaskBtn?.addEventListener('click', addTask);
    taskInput?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            addTask();
        }
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('toggle-task')) {
            const taskId = e.target.dataset.id;
            fetch(`/tasks/${taskId}/toggle`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(response => response.json()).then(data => {});
        }
    });

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.delete-task');
        if (btn) {
            const taskId = btn.dataset.id;
            fetch(`/tasks/${taskId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    btn.closest('li').remove();
                    document.dispatchEvent(new Event('tasks-changed'));
                }
            });
        }
    });
})();
</script>