 <!--
  - @copyright Copyright (c) 2020 John Molakvoæ <skjnldsv@protonmail.com>
  -
  - @author John Molakvoæ <skjnldsv@protonmail.com>
  -
  - @license GNU AGPL version 3 or any later version
  -
  - This program is free software: you can redistribute it and/or modify
  - it under the terms of the GNU Affero General Public License as
  - published by the Free Software Foundation, either version 3 of the
  - License, or (at your option) any later version.
  -
  - This program is distributed in the hope that it will be useful,
  - but WITHOUT ANY WARRANTY; without even the implied warranty of
  - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  - GNU Affero General Public License for more details.
  -
  - You should have received a copy of the GNU Affero General Public License
  - along with this program.  If not, see <http://www.gnu.org/licenses/>.
  -
  -->
<template>
	<HeaderMenu id="unified-search" class="unified-search" @open="onOpen">
		<template #trigger>
			<span class="icon-search-white" />
		</template>
		<div class="unified-search__input-wrapper">
			<input ref="input"
				v-model="query"
				class="unified-search__input"
				type="search"
				:placeholder="t('core', 'Search for {types} …', { types: formattedTypes })"
				@input="onInputDebounced">
		</div>
		<pre>{{ results }}</pre>
	</HeaderMenu>
</template>

<script>
import HeaderMenu from '../components/HeaderMenu'
import debounce from 'debounce'
import { getTypes, search } from '../services/UnifiedSearchService'

const minSearchLength = 2

export default {
	name: 'UnifiedSearch',

	components: {
		HeaderMenu,
	},

	data() {
		return {
			types: [],
			results: {},
			loading: {},
			query: '',
			minSearchLength,
		}
	},

	computed: {
		formattedTypes() {
			return this.types.map(type => type.charAt(0).toUpperCase() + type.substr(1).toLowerCase()).join(', ')
		},
	},

	async created() {
		this.types = await getTypes()
		console.debug('Unified Search initialized with the following providers', this.types)
	},

	methods: {
		async onOpen() {
			this.focusInput()

			// Update types list in the background
			this.types = await getTypes()
		},

		focusInput() {
			this.$nextTick(() => {
				this.$refs.input.focus()
				this.$refs.input.select()
			})
		},

		async onInput(e) {
			// Do not search if not long enough
			if (this.query && this.query.trim().length < minSearchLength) {
				return
			}

			this.types.forEach(async type => {
				const results = await search(type, this.query)
				this.$set(this.results, type, results.data.entries)
			})
		},

		onInputDebounced: debounce(function(e) {
			this.onInput(e)
		}, 200),
	},
}
</script>

<style lang="scss" scoped>
.unified-search {
	&__input-wrapper {
		position: sticky;
		background-color: var(--color-main-background);
		top: 0;
	}

	&__input {
		margin: 8px;
		height: 34px;
		width: calc(100% - 2 * 8px);
	}
}

</style>
