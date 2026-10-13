@php
    $techIntervId = $technicalIntervention->id ?? null;
@endphp

<script>
    
(() => {
    window.techIntervId = window.techIntervId || @json($techIntervId);
    $('.select2').select2({ width: '100%' });
    let searchInput = document.getElementById("searchEquipment");
    

    function getAddedEquipmentIds() {
        let addedIds = [];
        document.querySelectorAll("#selectedEquipments tr").forEach(row => {
            addedIds.push(row.dataset.equipmentId);
        });
        return addedIds;
    }

    // 🔍 AJAX: Search Equipment
    if (searchInput) {
        searchInput.addEventListener("input", function () {
            let query = this.value.trim();
            let list = document.getElementById("equipmentList");
            if (query.length < 2) {
                if (list) {
                    list.innerHTML = "";
                    list.style.display = "none";
                }
                return;
            }

            fetch(`/equipments/search?q=${query}`, {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(response => response.json())
            .then(data => {
                if (!list) return;
                list.innerHTML = "";

                if (data.length === 0) {
                    list.style.display = "none";
                    return;
                }
                list.style.display = "block";

                data.forEach(equipment => {
                    if (!getAddedEquipmentIds().includes(equipment.id.toString())) {
                        let matchedRefs = [];
                        if (equipment.suppliers) {
                            equipment.suppliers.forEach(s => {
                                if (s.pivot && s.pivot.supplier_reference && s.pivot.supplier_reference.toLowerCase().includes(query.toLowerCase())) {
                                    matchedRefs.push(s.pivot.supplier_reference);
                                }
                            });
                        }

                        let item = document.createElement("li");
                        item.classList.add("list-group-item", "d-flex", "justify-content-between", "align-items-center");
                        
                        let refHtml = `<strong>${equipment.reference}</strong>`;
                        if (matchedRefs.length > 0) {
                            refHtml += ` <span class="text-muted small">(${matchedRefs.join(', ')})</span>`;
                        }

                        item.innerHTML = `
                            <span>${refHtml} - ${equipment.name}</span>
                            <button type="button" class="btn btn-primary btn-sm add-equipment"
                                    data-id="${equipment.id}"
                                    data-name="${equipment.name}"
                                    data-reference="${equipment.reference}">
                                <i class="bi bi-plus-circle"></i> {{ __('locale.add') }}
                            </button>`;
                        list.appendChild(item);
                    }
                });
            });
        });
    }
    // ➕ AJAX: Add Equipment Without Refresh
    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("add-equipment")) {
            event.preventDefault();
            let id = event.target.dataset.id;
            let name = event.target.dataset.name;
            let reference = event.target.dataset.reference;
            let localisation = prompt("{{ __('locale.enter_localisation') }}", "");
            let quantity = prompt("{{ __('locale.enter_quantity') }}", "1");
            let description = prompt("{{ __('locale.enter_description') }}", "");

            if (!quantity || isNaN(quantity) || quantity <= 0) {
                alert("{{ __('locale.invalid_input') }}");
                return;
            }

            if (window.techIntervId) {

                fetch(`/tech_intervs/${window.techIntervId}/equipments`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        equipment_id: id,
                        localisation: localisation,
                        description: description,
                        quantity: quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let selectedEquipments = document.getElementById("selectedEquipments");
                        let newRow = document.createElement("tr");
                        newRow.dataset.equipmentId = data.equipment_id;
                        newRow.innerHTML = `
                            <td>${data.reference}</td>
                            <td>${data.name}</td>
                            <td>${data.localisation ?? ''}</td>
                            <td>${data.quantity}</td>
                            <td>${data.description ?? ''}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-equipment" data-equipment-id="${data.equipment_id}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>`;
                        selectedEquipments.appendChild(newRow);
                        event.target.closest("li").remove();
                    }
                });
            }
        }
    });

    // ❌ AJAX: Remove Equipment Without Refresh
    document.addEventListener("click", function (event) {
        if (event.target.closest(".remove-equipment")) {
            let button = event.target.closest(".remove-equipment");
            let equipmentId = button.dataset.equipmentId;
            let row = button.closest("tr");

            if (confirm("{{ __('locale.confirm_remove_equipment') }}")) {
                fetch(`/tech_intervs/${window.techIntervId}/equipments/${equipmentId}`, {
                    method: "DELETE",
                    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        row.remove();
                    }
                });
            }
        }
    });
})();
</script>
