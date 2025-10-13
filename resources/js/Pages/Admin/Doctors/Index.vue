<script setup>
	import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
	import { reactive, ref, onMounted, computed } from 'vue'
	import { usePage } from '@inertiajs/vue3'
	import axios from 'axios'

	const api = axios.create({ headers: { 'X-Requested-With' : 'XMLHttpRequest' }})
	const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
	if(token) api.defaults.headers.common['X-CSRF-TOKEN'] = token

	const page = usePage()
	const flash = computed(()=> page.props.value?.flash ?? {})

	const loading = ref(false)
	const items = ref([])

	const form = reactive({

		name: '', email: '', password: '',
		designation: '', speciality: '', phone: '', about: ''
	})
	const errors = ref({})
	const hasErrors = computed(() => !!errors.value && Object.keys(errors.value).length > 0)

	const showEdit = ref(false)
	const editing = ref(null)
	const editForm = reactive({

		name: '', email: '', password: '',
		designation: '', speciality: '', phone: '', about: ''
	})

	async function fetchList()
	{
		loading.value = true
		try 
		{
			const { data } = await api.get('/admin/doctors/list')
			items.value = data
		} 
		catch(e) 
		{
			alert(e?.response?.data?.message || 'Failed to load doctors')
		}

		finally
		{
			loading.value = false
		}
	}

	function resetForm()
	{
		form.name = ''
		form.email = ''
		form.password = ''
		form.designation = ''
		form.speciality = ''
		form.phone = ''
		form.about = ''
		errors.value = {}
	}

	async function submit()
	{
		errors.value = {}

		try 
		{
			const { data } = await api.post('/admin/doctors', { ...form })
			items.value.unshift(data.doctor)
			resetForm()
			alert('Doctor Created & Email Sent!')	
		} 
		catch(e) 
		{
			if(e?.response?.status === 422)
			{
				errors.value = e.response.data.errors || {}
				const msg = Object.values(errors.value || {}).flat().join('\n')
				if(msg) alert(msg)
			}
		else
		{
			alert(e?.response?.data?.message || 'Failed to create doctor')
		}
	  }
	}

	function openEdit(row)
	{
		editing.value = row
		editForm.name = row.name
		editForm.email = row.email
		editForm.password = ''
		editForm.designation = row.designation || ''
		editForm.speciality = row.speciality || ''
		editForm.phone = row.phone || ''
		editForm.about = row.about || ''
		showEdit.value = true
	}

	async function saveEdit()
	{
		if(!editing.value) return
			try 
			{
				const { data } = await api.post(`/admin/doctors/${editing.value.id}`, {...editForm})
				const idx = items.value.findIndex(x => x.id === editing.value.id)
				if(idx > -1 ) items.value[idx] = data.doctor
					showEdit.value = false
				alert('Doctor Updated')
			} 
			catch(e) 
			{
				const errs = e?.response?.data?.errors
				if(errs)
				{
					alert(Object.values(errs).flat().join('\n'))
				} 

				else
				{
					alert(e?.response?.data?.message || 'Failed to update')
				}
			}
		}
	async function removeRow(row)
	{
		if(!confirm(`Delete ${row.name}? This will also remove the user account.`)) return

		try 
		{
			await api.delete(`/admin/doctors/${row.id}`)
			items.value = items.value.filter(x => x.id !== row.id)
		} 
		catch(e) 
		{
			alert(e?.response?.data?.message || 'Failed to delete')
		}
	}

	onMounted(fetchList)

</script>

<template>
	<AuthenticatedLayout title="Admin: Doctors">
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
			<div class="border rounded p-4 space-y-3">
				<h2 class="text-lg font-semibold">Add New Doctor</h2>

				<div v-if="flash.success" class="text-green-600">{{ flash.success }}</div>
				<div v-if="flash.error" class="text-red-600">{{ flash.error }}</div>

				<div class="grid grid-cols-2 gap-3">
					<label class="col-span-2">Name
						<input type="text" v-model="form.name" class="input">
						<p v-if="errors.name" class="err">{{ errors.name[0] }}</p>
					</label>

					<label class="col-span-2">Email
						<input type="email" v-model="form.email" class="input">
						<p v-if="errors.email" class="err">{{ errors.email[0] }}</p>
					</label>

					<label class="col-span-2">Password
						<input type="password" v-model="form.password" class="input" placeholder="Min 8 chars">
						<p v-if="errors.password" class="err">{{ errors.password[0] }}</p>
					</label>

					<label>Designation
						<input type="text" v-model="form.designation" class="input">						
					</label>

					<label>Speciality
						<input type="text" v-model="form.speciality" class="input">						
					</label>

					<label>Phone
						<input type="text" v-model="form.phone" class="input">	
					</label>

					<label class="col-span-2">About
						<textarea v-model="form.about" class="input"></textarea>
					</label>
				</div>

				<div v-if="hasErrors" class="text-red-600 text-sm">
					<div v-for="( msgs, key) in errors" :key="key">
						{{ Array.isArray(msgs) ? msgs[0] : msgs }}
					</div>
				</div>
				<button class="btn-primary" @click="submit">Save Doctor</button>
				

				<div class="border rounded p-4">
					<div class="flex items-center justify-between mb-3">
						<h2 class="text-lg font-semibold">Doctors</h2>
						<button class="btn" @click="fetchList" :disabled="loading">Reload</button>
					</div>

					<table class="w-full">
						<thead>
							<tr class="text-left">
								<th class="p-2">Name</th>
								<th class="p-2">Email</th>
								<th class="p-2">Speciality</th>
								<th class="p-2">Phone</th>
								<th class="p-2"></th>
							</tr>
						</thead>
						<tbody>
							<tr v-if="loading"><td class="p-2" colspan="=5">Loading...</td></tr>
							<tr v-for="d in items" :key="d.id" class="border-t">
								<td class="p-2">{{ d.name }}</td>
								<td class="p-2">{{ d.email }}</td>
								<td class="p-2">{{ d.speciality }}</td>
								<td class="p-2">{{ d.phone }}</td>
								<td class="p-2 flex gap-2">
									<button class="btn" @click="openEdit(d)">Edit</button>
									<button class="btn-danger" @click="removeRow(d)">Delete</button>
								</td>
							</tr>
							<tr v-if="!loading && items.length == 0">
								<td class="p-2 text-gray-500" colspan="5">No Doctors yet...</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			
			<div v-if="showEdit" class="fixed inset-0 bg-black/50 flex items-center justify-center">
				
		  </div>
		</div>

		<div v-if="showEdit" class="fixed inset-0 bg-black/50 flex items-center justify-center">
			<div class="bg-white rounded p-6 w-full max-w-xl space-y-3">
				<div class="flex items-center justify-between">
					<h3 class="text-lg font-semibold">Edit Doctor</h3>
					<button class="btn" @click="showEdit=false">X</button>
				</div>

				<div class="grid grid-cols-2 gap-3">
					<label class="col-span-2">
						Name
						<input type="text" v-model="editForm.name" class="input">
					</label>

					<label class="col-span-2">
						Email
						<input type="email" v-model="editForm.email" class="input">
					</label>
					<label class="col-span-2">
						Password (leave blank to keep)
						<input type="password" v-model="editForm.password" class="input">
					</label>

					<label>
						Designation
						<input type="text" v-model="editForm.designation" class="input">
					</label>
					<label>
						Speciality
						<input type="text" v-model="editForm.speciality" class="input">
					</label>

					<label>
						Phone
						<input type="text" v-model="editForm.phone" class="input">
					</label>

					<label>
						About
						<textarea v-model="editForm.about" class="input"></textarea>
					</label>
				</div>

				<div class="flex gap-2">
					<button class="btn-primary" @click="saveEdit">Save</button>
					<button class="btn" @click="showEdit=false">Cancel</button>
				</div>
			</div>
		</div>
	</AuthenticatedLayout>
</template>

<style scoped>
	.input { @apply w-full border rounded px-3 py-2; }
	.btn { @apply px-3 py-2 rounded border; }
	.btn-primary { @apply px-4 py-2 rounded bg-blue-600 text-white; }
	.btn-danger { @apply px-3 py-2 rounded bg-red-600 text-white; }
</style>