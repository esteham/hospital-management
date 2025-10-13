import axios from 'axios'

const api = axios.create({

	headers: { 'X-Requested-With': 'XMLHttpRequest'}
})

const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
if(token) api.defaults.headers.common['X-CSRF-TOKEN'] = token

export default api