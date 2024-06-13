        </div>
    </div>
    <footer class="uk-section uk-section-small uk-text-center">
        <p>&copy; 2024 Task Manager. Все права защищены.</p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.7.6/js/uikit.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.7.6/js/uikit-icons.min.js"></script>

    <script>
        document.querySelectorAll('.uk-icon-button[uk-icon="trash"]').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const taskId = this.closest('form').querySelector('input[name="task_id"]').value;
                const currentUrl = window.location.href;
                UIkit.modal.confirm('Вы уверены, что хотите удалить задачу?').then(() => {
                    const form = document.createElement('form');
                    form.method = 'POST';

                    // Определение базового URL
                    const baseURL = window.location.origin + window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/'));

                    // Формирование правильного пути к delete.php
                    form.action = baseURL + '/../control/delete.php';
                    
                    const inputTaskId = document.createElement('input');
                    inputTaskId.type = 'hidden';
                    inputTaskId.name = 'task_id';
                    inputTaskId.value = taskId;

                    const inputRedirectUrl = document.createElement('input');
                    inputRedirectUrl.type = 'hidden';
                    inputRedirectUrl.name = 'redirect_url';
                    inputRedirectUrl.value = currentUrl;
                    
                    form.appendChild(inputTaskId);
                    form.appendChild(inputRedirectUrl);
                    document.body.appendChild(form);
                    form.submit();
                });
            });
        });
    </script>
	
</body>
</html>
