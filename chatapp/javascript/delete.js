function deleteMessage(messageId) {
    if (confirm("Вы уверены, что хотите удалить это сообщение?")) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_message.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            const messageContainer = document.getElementById(`message-${messageId}`);
            if (messageContainer) {
            messageContainer.remove();
            } else {
            console.error('Message with id', messageId, 'not found on the page.');
            }
    
        } else {
            alert("Ошибка при удалении сообщения.");
            console.error("Ошибка:", xhr.status, xhr.statusText);
        }
        };
        xhr.onerror = function() {
        alert("Ошибка при удалении сообщения.");
        console.error("Ошибка соединения");
        };
        xhr.send(`message_id=${messageId}`);
    }
    }