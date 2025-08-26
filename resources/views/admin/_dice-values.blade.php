<section>
  <table class="dice-values">
    <tr>
      <th>{{ __('admin.dice_values.1_dice') }}</th>
      <th>{{ __('admin.dice_values.2_dice') }}</th>
      <th>{{ __('admin.dice_values.3_dice') }}</th>
    </tr>
    <tr>
      <td>
        <div class="dice-values__wrapper">
          <span class="dice-values__dices">
            <span><x-icon-dice type="red" />,&nbsp;</span>
            <span><x-icon-dice type="green" /></span>
          </span>
          <span class="dice-values__text">
            <span class="dice-values__percentage">86-96%</span>
            <span class="dice-values__value">2</span>
          </span>
        </div>
      </td>
      <td>
        <div class="dice-values__wrapper">
          <span class="dice-values__dices">
            <span><x-icon-dice type="red" /><x-icon-dice type="green" />,&nbsp;</span>
            <span><x-icon-dice type="red" /><x-icon-dice type="red" /></span>
          </span>
          <span class="dice-values__text">
            <span class="dice-values__percentage">81-83%</span>
            <span class="dice-values__value">3</span>
          </span>
        </div>
      </td>
      <td>
        <div class="dice-values__wrapper">
          <span class="dice-values__dices">
            <span><x-icon-dice type="red" /><x-icon-dice type="red" /><x-icon-dice type="green" /></span>
          </span>
          <span class="dice-values__text">
            <span class="dice-values__percentage">68%</span>
            <span class="dice-values__value">5</span>
          </span>
        </div>
      </td>
    </tr>

    <tr>
      <td>
        <div class="dice-values__wrapper">
          <span class="dice-values__dices">
            <span><x-icon-dice type="blue" /></span>
          </span>
          <span class="dice-values__text">
            <span class="dice-values__percentage">59%</span>
            <span class="dice-values__value">3</span>
          </span>
        </div>
      </td>
      <td>
        <div class="dice-values__wrapper">
          <span class="dice-values__dices">
            <span><x-icon-dice type="red" /><x-icon-dice type="blue" />,&nbsp;</span>
            <span><x-icon-dice type="green" /><x-icon-dice type="green" />,&nbsp;</span>
            <span><x-icon-dice type="green" /><x-icon-dice type="blue" /></span>
          </span>
          <span class="dice-values__text">
            <span class="dice-values__percentage">49-57%</span>
            <span class="dice-values__value">4</span>
          </span>
        </div>
      </td>
      <td>
        <div class="dice-values__wrapper">
          <span class="dice-values__dices">
            <span><x-icon-dice type="red" /><x-icon-dice type="green" /><x-icon-dice type="green" />,&nbsp;</span>
            <span><x-icon-dice type="red" /><x-icon-dice type="red" /><x-icon-dice type="red" />,&nbsp;</span>
            <span><x-icon-dice type="red" /><x-icon-dice type="green" /><x-icon-dice type="blue" />,&nbsp;</span>
            <span><x-icon-dice type="red" /><x-icon-dice type="red" /><x-icon-dice type="blue" /></span>
          </span>
          <span class="dice-values__text">
            <span class="dice-values__percentage">44-50%</span>
            <span class="dice-values__value">6</span>
          </span>
        </div>
      </td>
    </tr>

    <tr>
      <td></td>
      <td>
        <div class="dice-values__wrapper">
          <span class="dice-values__dices">
            <span><x-icon-dice type="blue" /><x-icon-dice type="blue" /></span>
          </span>
          <span class="dice-values__text">
            <span class="dice-values__percentage">19%</span>
            <span class="dice-values__value">5</span>
          </span>
        </div>
      </td>
      <td>
        <div class="dice-values__wrapper">
          <span class="dice-values__dices">
            <span><x-icon-dice type="green" /><x-icon-dice type="green" /><x-icon-dice type="blue" />,&nbsp;</span>
            <span><x-icon-dice type="green" /><x-icon-dice type="green" /><x-icon-dice type="green" />,&nbsp;</span>
            <span><x-icon-dice type="red" /><x-icon-dice type="blue" /><x-icon-dice type="blue" />,&nbsp;</span>
            <span><x-icon-dice type="green" /><x-icon-dice type="blue" /><x-icon-dice type="blue" /></span>
          </span>
          <span class="dice-values__text">
            <span class="dice-values__percentage">14-27%</span>
            <span class="dice-values__value">7</span>
          </span>
        </div>
      </td>
    </tr>

    <tr>
      <td></td>
      <td></td>
      <td>
        <div class="dice-values__wrapper">
          <span class="dice-values__dices">
            <span><x-icon-dice type="blue" /><x-icon-dice type="blue" /><x-icon-dice type="blue" /></span>
          </span>
          <span class="dice-values__text">
            <span class="dice-values__percentage">3%</span>
            <span class="dice-values__value">8</span>
          </span>
        </div>
      </td>
    </tr>
  </table>

  <table class="effect-values">
    <tr>
      <th colspan="2">{{ __('admin.dice_values.unique_effect') }}</th>
      <th colspan="2">{{ __('admin.dice_values.permanent_effect') }}</th>
    </tr>
    <tr>
      <td>{{ __('admin.dice_values.damage_plus') }}</td>
      <td class="effect-values__value">{{ __('admin.dice_values.base') }}</td>
      <td>{{ __('admin.dice_values.damage_plus') }}</td>
      <td class="effect-values__value">{{ __('admin.dice_values.base') }} * 2</td>
    </tr>
    <tr>
      <td>{{ __('admin.dice_values.damage_minus') }}</td>
      <td class="effect-values__value">{{ __('admin.dice_values.base') }}</td>
      <td>{{ __('admin.dice_values.damage_minus') }}</td>
      <td class="effect-values__value">{{ __('admin.dice_values.base') }} * 2</td>
    </tr>
    <tr>
      <td>{{ __('admin.dice_values.counter') }}</td>
      <td class="effect-values__value">{{ __('admin.dice_values.base') }} {{ __('admin.dice_values.counter_aclaration') }}</td>
      <td>{{ __('admin.dice_values.counter_per_strike') }}</td>
      <td class="effect-values__value">{{ __('admin.dice_values.base') }} * 2 {{ __('admin.dice_values.counter_aclaration') }}</td>
    </tr>
    <tr>
      <td>{{ __('admin.dice_values.versatile') }}</td>
      <td class="effect-values__value">+1</td>
      <td>{{ __('admin.dice_values.counter_once') }}</td>
      <td class="effect-values__value">{{ __('admin.dice_values.base') }} * 1.5 {{ __('admin.dice_values.counter_aclaration') }}</td>
    </tr>
    <tr>
      <td>{{ __('admin.dice_values.damage_plus_long') }}</td>
      <td class="effect-values__value">{{ __('admin.dice_values.base') }} * 1.5</td>
      <td></td>
      <td class="effect-values__value"></td>
    </tr>
    <tr>
      <td>{{ __('admin.dice_values.damage_minus_long') }}</td>
      <td class="effect-values__value">{{ __('admin.dice_values.base') }} * 1.5</td>
      <td></td>
      <td class="effect-values__value"></td>
    </tr>
    <tr>
      <td>{{ __('admin.dice_values.draw') }} 1</td>
      <td class="effect-values__value">+2</td>
      <td></td>
      <td class="effect-values__value"></td>
    </tr>
    <tr>
      <td>{{ __('admin.dice_values.cicle') }} 1</td>
      <td class="effect-values__value">+1</td>
      <td></td>
      <td class="effect-values__value"></td>
    </tr>
    <tr>
      <td>{{ __('admin.dice_values.trick') }}</td>
      <td class="effect-values__value">+1</td>
      <td></td>
      <td class="effect-values__value"></td>
    </tr>
    <tr>
      <td>{{ __('admin.dice_values.restriction') }}</td>
      <td class="effect-values__value">-1</td>
      <td></td>
      <td class="effect-values__value"></td>
    </tr>
    <tr>
      <td>{{ __('admin.dice_values.unique') }}a</td>
      <td class="effect-values__value">-1</td>
      <td></td>
      <td class="effect-values__value"></td>
    </tr>
    <tr>
      <td>{{ __('admin.dice_values.area') }}</td>
      <td class="effect-values__value">+1</td>
      <td></td>
      <td class="effect-values__value"></td>
    </tr>
  </table>
</section>