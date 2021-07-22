<template>
	<Multiselect
		v-model="selected"
		class="group-multiselect"
		:placeholder="t('adminrightsubgranting', 'None')"
		track-by="gid"
		label="displayName"
		:options="groups"
		open-direction="bottom"
		:multiple="true"
		:allow-empty="true" />
</template>

<script>
import Multiselect from '@nextcloud/vue/dist/Components/Multiselect'
import { generateUrl } from '@nextcloud/router'
import axios from '@nextcloud/axios'

export default {
	name: 'GroupSelect',
	components: {
		Multiselect,
	},
	props: {
		groups: {
			type: Array,
			default: () => [],
		},
		setting: {
			type: Object,
			required: true,
		},
		initialState: {
			type: Array,
			required: true,
		},
	},
	data() {
		const selected = []
		for (const initialGroup of this.initialState) {
			if (initialGroup.class === this.setting.class) {
				const group = this.groups.find((group) => group.gid === initialGroup.group_id)
				selected.push(group)
			}
		}
		return {
			selected,
		}
	},
	watch: {
		selected() {
			this.saveGroups()
		},
	},
	methods: {
		async saveGroups() {
			const data = {
				groups: this.selected,
				class: this.setting.class,
			}
			await axios.post(generateUrl('/apps/adminrightsubgranting/') + 'authorizedgroups/saveSettings', data)
		},
		async saveGroups2(newGroups, removedGroups) {
			for (const group of newGroups) {
				try {
					await axios.post(generateUrl('/apps/adminrightsubgranting/') + 'authorizedgroups', {
						groupId: group.gid,
						class: this.setting.class,
					})
				} catch (error) {
					// this.error = true
					// console.error('Error fetching guests list', error)
				}
			}
			for (const group of removedGroups) {
				try {
					await axios.delete(generateUrl('/apps/adminrightsubgranting/') + 'authorizedgroups', {
						groupId: group.gid,
						class: this.setting.class,
					})
				} catch (error) {
					// this.error = true
					// console.error('Error fetching guests list', error)
				}
			}
		},
	},
}
</script>

<style lang="scss">
.group-multiselect {
	width: 100%;
	margin-right: 0;
}
</style>
