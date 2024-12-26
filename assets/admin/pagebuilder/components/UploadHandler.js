export default function uploadFile(file) {
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        const formData = new FormData();

        // Добавляем файл в FormData
        formData.append('file', file);

        xhr.open('POST', '/pagebuilder/upload-image', true); // Указываем URL для отправки файла

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    resolve(JSON.parse(xhr.responseText));
                } else {
                    reject(xhr.statusText);
                }
            }
        };

        xhr.onerror = function() {
            reject('Network error');
        };

        xhr.send(formData); // Отправляем данные
    });
}
