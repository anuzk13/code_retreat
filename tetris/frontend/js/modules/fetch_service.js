const api_url = '/back';
let playerToken = '';

const postData = (resource = ``, data = {}) => {
    const url = `${api_url}/${resource}`;
    return fetch(url, {
        method: "POST",
        cache: "no-cache",
        headers: {
            "Content-Type": "application/json; charset=utf-8",
            "Authorization": playerToken
        },
        body: JSON.stringify(data),
    })
        .then(response => response.json()) // parses response to JSON
        .catch(error => console.error(`Fetch Error =\n`, error));
};

const getData = (resource = ``) => {
    const url = `${api_url}/${resource}`;
    return fetch(url, {
        method: "GET",
        cache: "no-cache",
        headers: {
            "Content-Type": "application/json; charset=utf-8",
            "Authorization": playerToken
        }
    })
        .then(response => response.json()) // parses response to JSON
        .catch(error => console.error(`Fetch Error =\n`, error));
};

const putData = (resource = ``, data = {}) => {
    const url = `${api_url}/${resource}`;
    return fetch(url, {
        method: "PUT",
        cache: "no-cache",
        headers: {
            "Content-Type": "application/json; charset=utf-8",
            "Authorization": playerToken
        },
        body: JSON.stringify(data),
    })
        .then(response => response.json()) // parses response to JSON
        .catch(error => console.error(`Fetch Error =\n`, error));
};

const getToken = () => playerToken;

const setToken = (token) => {
    playerToken = token;
}


export { postData, getData, putData, getToken, setToken }
