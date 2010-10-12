<li>
	<img src="/images/placeholder.png"{{if type!==''}} class="${type.toLowerCase()}"{{/if}} alt="" />
	<a href="#!mailbox:/${global}/?">${name} {{if unread>0}}(${unread}){{/if}} <span class="small">${mails}</span></a>
</li>
