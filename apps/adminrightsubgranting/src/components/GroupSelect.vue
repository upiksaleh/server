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
	},
	data() {
		const defaultGroups = []
		/* if (this.required && this.groups.length === 1) {
			defaultGroups.push(this.groups[0])
			this.$emit('input', defaultGroups.map(group => group.gid))
		} */
		return {
			selected: defaultGroups,
		}
	},
	watch: {
		selected(newSelected, oldSelected) {
			if (newSelected === oldSelected) {
				return
			}
			const removedGroups = []
			oldSelected.forEach((group) => {
				if (!newSelected.includes(group)) {
					removedGroups.push(group)
				}
			})
			const newGroups = []
			newSelected.forEach((group) => {
				if (!oldSelected.includes(group)) {
					newGroups.push(group)
				}
			})
			this.saveGroups()
		},
	},
	methods: {
		async saveGroups() {
			const data = {
				groups: this.selected,
				class: this.setting.class,
			}
			// eslint-disable-next-line no-console
			console.log(data)
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
