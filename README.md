## GL - Geo Location (MODx Revolution)

```
<p>
    Ваш город:
    <span class="gl-current-select">[[!+gl.current.city.name_ru]]</span>
</p>

[[!gl.modal?
	&class=`glCity`
]]

<h5>Реальное местоположение</h5>
<code>
[[!+gl.real.city.name_ru]]<br>
[[!+gl.real.region.name_ru]]<br>
[[!+gl.real.country.name_ru]]<br>  
</code>


<h5>Выбранное местоположение</h5>
<code>
[[!+gl.current.city.name_ru]]<br>
[[!+gl.current.region.name_ru]]<br>
[[!+gl.current.country.name_ru]]<br>
</code>

<h5>Контакты</h5>
<code>
phone: [[!+gl.current.data.phone]]<br>
email: [[!+gl.current.data.email]]<br>
</code>
```
