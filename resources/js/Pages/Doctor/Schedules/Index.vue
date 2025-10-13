<script setup>
	import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
	import { ref, reactive, onMounted } from 'vue'
	import api from '@/lib/api'

	const days = [
					{ v: 'sat', t: 'Saturday'},
					{ v: 'sun', t: 'Sunday'},
					{ v: 'mon' , t: 'Monday'},
					{ v: 'tue', t: 'Tuesday'},
					{ v: 'wed' , t: 'Wednesday'},
					{ v: 'thu', t: 'Thursday'},
					{ v: 'fri', t: 'Friday'},
				]

	const items = ref([])
	const loading = ref(false)
	const editing = ref(null)

	const form = reactive({

		day_of_week: 'sat',
		start_time: '09:00',
		end_time: '13:00',
		slot_minutes: 15,
		max_patients_per_day: 20,
		fee: 500,
	})

	function toHHmm(t)
	{
		if(!t) return t
		if(/^\d{2}:\d{2}:\d{2}$/.test(t)) return t.slice(0, 5)
		const m = t.match(/^(\d{1,2}):(\d{2})/)
		return m ? `${m[1].padStart(2, '0')}:${m[2]}` : t
	}

	async function fetchList()
	{
		loading.value = true

		try 
		{
			const { data } = await api.get('/doctor/schedules/list')
			items.value = data			
		} 
		catch(e) 
		{
			alert(e.response?.data?.message || 'Failed to load schedules.')
		}

		finally
		{
			loading.value = false
		}
	}

	function resetForm()
	{
		editing.value = null
		form.day_of_week = 'sat'
		form.start_time = '9:00'
		form.end_time = '13:00'
		form.slot_minutes = 15
		form.max_patients_per_day = 20
		form.fee = 500
	}

	async function save()
	{
		const payload = {

			...form,
			start_time: toHHmm(form.start_time),
			end_time: toHHmm(form.end_time),
		}

		try 
		{
			if(editing.value)
			{
				const { data } = await api.put(`/doctor/schedules/${editing.value.id}`, payload)
				const idx = items.value.findIndex( i => i.id === editing.value.id)

				if(idx > -1 ) items.value[idx] = data
				resetForm()
			}

			else
			{
				const { data } = await api.post('/doctor/schedules', payload)
				items.value.push(data)
				resetForm()
			}
		} 
		catch(e) 
		{
			alert(e.response?.data?.message || 'Validation error')
		}
	}

	function edit(row)
	{
		editing.value = row
		form.day_of_week = row.day_of_week
		form.start_time = toHHmm(row.start_time)
		form.end_time = toHHmm(row.end_time)
		form.slot_minutes = row.slot_minutes
		form.max_patients_per_day = row.max_patients_per_day
		form.fee = row.fee
	}

	async function removeRow(row)
	{
		if(!confirm('Delete this schedule?')) return

		await api.delete(`/doctor/schedules/${row.id}`)

		items.value = items.value.filter(i => i.id !== row.id)
	}

	onMounted(fetchList)
</script>

<template>
	<AuthenticatedLayout title="My Schedules">
		<div class="space-y-6">
			<div class="border rounded p-4 space-y-3">
				<h2 class="font-medium">{{ editing ? 'Edit Schedule' : 'Create Schedule'}}</h2>

				<div class="grid grid-cols-2 gap-3">
					<label class="col-span-2">
						Day
						<select v-model="form.day_of_week" class="input">
							<option v-for="d in days" :key="d.v" :value="d.v">{{ d.t }}</option>
						</select>
					</label>

					<label>
						Start Time
						<input v-model="form.start_time" type="time" class="input">
					</label>

					<label>
						End Time

						<input v-model="form.end_time" type="time" class="input">
					</label>

					<label>
						Slot (Minutes)

						<input v-model.number="form.slot_minutes" type="number" min="5" class="input">
					</label>

					<label>
						Max patients/day

						<input v-model.number="form.max_patients_per_day" type="number" min="1" class="input">
					</label>

					<label class="col-span-2">
						Fees ($)

						<input v-model.number="form.fee" type="number" min="0" class="input">
					</label>
				</div>

				<div class="flex gap-2">
					<button class="btn-primary" @click="save">{{ editing ? 'Update' : 'Save' }}</button>
					<button class="btn" v-if="editing" @click="resetForm">Cancel</button>
				</div>
			</div>

			<div class="border rounded">
				<table class="w-full">
					<thead>
						<tr class="text-left">
							<th class="p-2">Day</th>
							<th class="p-2">Time</th>
							<th class="p-2">Slot</th>
							<th class="p-2">Max/day</th>
							<th class="p-2">Fee</th>
							<th class="p-2"></th>
						</tr>
					</thead>
					<tbody>
						<tr v-if="loading"><td class="p-2" colspan="6">Loading...</td></tr>
						<tr v-for="row in items" :key="row.id" class="border-t">
							<td class="p-2 uppercase">
								{{ row.day_of_week }}
							</td>

							<td class="p-2">
								{{ row.start_time}} - {{ row.end_time }}
							</td>

							<td class="p-2">
								{{ row.slot_minutes}} min
							</td>

							<td class="p-2">
								{{ row.max_patients_per_day }}
							</td>

							<td class="p-2">
								$ {{ row.fee }}
							</td>

							<td class="p-2 flex gap-2">
								<button class="btn" @click="edit(row)">Edit</button>
								<button class="btn-danger" @click="removeRow(row)">Delete</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</AuthenticatedLayout>
</template>

<style scoped>
	.input { @apply w-full border rounded px-3 py-2; }
	.btn { @apply px-3 py-2 rounded border; }
	.btn-primary { @apply px-3 py-2 rounded bg-blue-600 text-white; }
	.btn-danger { @apply px-3 py-2 rounded bg-red-600 text-white; }
</style>