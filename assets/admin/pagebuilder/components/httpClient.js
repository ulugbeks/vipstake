export default class HttpClient {
    get(url) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', url, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

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

            xhr.send();
        });
    }

    post(url, data) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', url, true);

            // Устанавливаем заголовок, чтобы сервер понял, что данные отправляются в формате JSON
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        resolve(JSON.parse(xhr.responseText));
                    } else {
                        const errorResponse = JSON.parse(xhr.responseText);
                        reject(errorResponse.error || xhr.statusText);
                    }
                }
            };

            xhr.onerror = function() {
                reject('Network error');
            };

            // Отправляем данные в формате JSON
            xhr.send(JSON.stringify(data));
        });
    }

    postFormData(url, formData) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', url, true);

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

            xhr.send(formData);
        });
    }
}