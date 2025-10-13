<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'

const api = axios.create({ headers: {'X-Requested-With': 'XMLHttpRequest' } })
const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
if(token) api.defaults.headers.common['X-CSRF-TOKEN'] = token

const loading = ref(false)
const rows = ref([])
const search = ref('')

const openRow = ref(null)
const mentionForm = reactive({

	schedule_id: null,
	doctor_id: null,
	text: ''
})

async function loadList()
{
	loading.value = true

	try 
	{
		const { data } = await api.get('/admin/schedules/list', { params: { search: search.value || '' } })

		rows.value = data
	} 
	catch(e) 
	{
		alert(e?.response?.data?.message || 'Failed to load schedules')
	}

	finally
	{
		loading.value = false
	}
}

function openMention(r)
{
	openRow.value = r.id
	mentionForm.schedule_id = r.id
	mentionForm.doctor_id = r.doctor_id
	mentionForm.text = ''
}

function cancelMention()
{
	openRow.value = null
	mentionForm.schedule_id = null
	mentionForm.doctor_id = null
	mentionForm.text = ''
}

async function sendMention()
{
	if(!mentionForm.text.trim()) return alert('Write a Message!')

		try 
		{
			await api.post('/admin/schedules/mention', 
				{ 
					schedule_id: mentionForm.schedule_id,
					doctor_id: mentionForm.doctor_id,
					message: mentionForm.text.trim(),
				})
			alert('Message Sent')
			cancelMention()
		} 
		catch(e) 
		{
			alert(e?.response?.data?.message || 'Failed to send message!')
		}
}

onMounted(loadList)
</script>

<template>
	<AuthenticatedLayout title="Doctors Schedules">
		<div class="space-y-4">
			<div class="flex items-center gap-3">
				<input v-model="search" @keyup.enter="loadList" placeholder="Search by doctors/email" class="input w-72">
				<button class="btn" @click="loadList" :disabled="loading">Search</button>
				<button class="btn" @click="()=>{search=''; loadList()}" :disabled="loading">Reset</button>
			</div>

			<div class="border rounded">
				<table class="w-full">
					<thead>
						<tr class="text-left">
							<th class="p-2">Doctor</th>
							<th class="p-2">Email</th>
							<th class="p-2">Day</th>
							<th class="p-2">Time</th>
							<th class="p-2">Slot</th>
							<th class="p-2">Max/day</th>
							<th class="p-2">Fees</th>
							<th class="p-2"></th>
						</tr>
					</thead>
					<tbody>
						<tr v-if="loading"><td class="p-2" colspan="8">Loading...</td></tr>

						<template v-for="r in rows" :key="r.id">
							<tr class="border-t">
								<td class="p-2">{{ r.doctor}}</td>
								<td class="p-2">{{ r.email}}</td>
								<td class="p-2">{{ r.day}}</td>
								<td class="p-2">{{ r.time}}</td>
								<td class="p-2">{{ r.slot}}</td>
								<td class="p-2">{{ r.max}}</td>
								<td class="p-2">{{ r.fee}}</td>
								<td class="p-2">
									<button class="btn" @click="openMention(r)">Mention</button>
								</td>
							</tr>
							<tr v-if="openRow === r.id" class="bg-gray-50 border-t">
								<td class="p-2" colspan="8">
									<div class="flex items-start gap-2">
										<textarea v-model="mentionForm.text" rows="3" class="input w-full" placeholder="Write a private message to the doctor...">										
										</textarea>
										<div class="flex flex-col gap-2">
											<button class="btn-primary" @click="sendMention">Send</button>
											<button class="btn" @click="cancelMention">Cancel</button>
										</div>
									</div>
								</td>
							</tr>
						</template>
						<tr v-if="!loading && rows.length=== 0">
							<td class="p-2 text-gray-500" colspan="8">No Schedules</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</AuthenticatedLayout>
</template>

<style scoped>
	.input { @apply border rounded px-3 py-2; }
	.btn { @apply px-3 py-2 rounded border; }
	.btn-primary { @apply px-3 py-2 rounded bg-blue-600 text-white; }
</style>