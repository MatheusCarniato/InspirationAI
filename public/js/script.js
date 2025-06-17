function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleSidebar').firstElementChild;

    if (sidebar.style.width === '50px') {
        sidebar.style.width = '250px';
        toggleBtn.className = 'bi bi-chevron-double-left';
    } else {
        sidebar.style.width = '50px';
        toggleBtn.className = 'bi bi-chevron-double-right';
    }

    const links = sidebar.getElementsByTagName('a');
    for (let i = 0; i < links.length; i++) {
        const linkText = links[i].getElementsByClassName('link-text')[0];
        linkText.style.display = (sidebar.style.width === '50px') ? 'none' : 'inline';
    }
}

function showPanoramaView(groupId) {
    $.ajax({
        url: '/inspirationAI/app/models/fetch_prompts.php',
        method: 'POST',
        data: { id_group: groupId },
        success: function (response) {
            const data = JSON.parse(response);
            let tableBody = '';

            data.forEach(function (prompt) {
                tableBody += `
                    <tr data-toggle="modal" data-target="#promptDetailsModal" onclick="showPromptDetails('${prompt.title_prompt}', '${prompt.content_prompt}', '${prompt.id_prompt}')">
                        <td>${prompt.id_prompt}</td>
                        <td><strong>${prompt.title_prompt}</strong></td>
                        <td>${prompt.date_created}</td>
                        <td>${prompt.date_modified}</td>
                        <td>${prompt.usage_cont}</td>
                        <td>${prompt.ratng}</td>
                    </tr>`;
            });

            document.querySelector('.table-view tbody').innerHTML = tableBody;
        }
    });
}

function showPromptDetails(title, content, id) {
    document.getElementById('prompt-title').innerText = title;
    document.getElementById('prompt-content').innerText = content;
    document.getElementById('id_prompt').value = id;
    $('#promptsModal').modal('hide');
    $('#ModalFavorito').modal('hide');
    $('#promptDetailsModal').modal('show');

}

function returnToPromptsModal() {
    $('#promptDetailsModal').modal('hide');
    $('#ModalFavorito').modal('hide');
    $('#promptsModal').modal('show');
}


function copPromptsText() {
    const content = document.getElementById('prompt-content');
    navigator.clipboard.writeText(content.innerText)
}

function toggleFavoriteModal() {
    if ($('#ModalFavorito').is(':visible')) {
        $('#ModalFavorito').modal('hide');
        $('#promptsModal').modal('show');
    } else {
        $('#promptsModal').modal('hide');
        $('#ModalFavorito').modal('show');
    }
}

function showAlertInDiv(message, type) {
    const messageDiv = document.getElementById('mensagem');

    // Cria uma estrutura de alerta
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    `;

    // Limpa qualquer mensagem anterior na div
    messageDiv.innerHTML = '';

    // Adiciona a nova mensagem à div
    messageDiv.appendChild(alertDiv);

    // Remove o alerta após 5 segundos(Menu)
    setTimeout(() => {
        alertDiv.classList.remove('show');
        alertDiv.classList.add('fade');
        setTimeout(() => {
            messageDiv.removeChild(alertDiv);
        }, 150); // Tempo adicional para a transição fade-out
    }, 5000);
}


document.addEventListener('DOMContentLoaded', function () {
    toggleSidebar();
});
document.querySelector('a[data-target="#ModalFavorito"]').addEventListener('click', toggleFavoriteModal);

