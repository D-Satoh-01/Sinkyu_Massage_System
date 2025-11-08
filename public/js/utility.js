//-- public/js/utility.js --//

/**
 * 郵便番号から住所を検索する汎用関数
 * @param {string} postalCode - 郵便番号（ハイフンあり/なし両方対応）
 * @returns {Promise<Object|null>} 住所情報オブジェクトまたはnull
 *   - address1: 都道府県
 *   - address2: 市区町村
 *   - address3: 町域
 */
async function searchAddressByPostalCode(postalCode) {
  // 郵便番号を数字のみに変換
  const cleanPostalCode = postalCode.replace(/[^\d]/g, '');

  // 7桁の数字かチェック
  if (cleanPostalCode.length !== 7) {
    return null;
  }

  try {
    // 郵便番号API呼び出し（zipcloud API使用）
    const response = await fetch(`https://zipcloud.ibsnet.co.jp/api/search?zipcode=${cleanPostalCode}`);
    const data = await response.json();

    if (data.status === 200 && data.results && data.results.length > 0) {
      return data.results[0];
    } else {
      return null;
    }
  } catch (error) {
    console.error('Address search error:', error);
    return null;
  }
}

/**
 * 郵便番号を整形する関数（XXX-XXXX形式）
 * @param {string} value - 入力された郵便番号
 * @returns {string} 整形された郵便番号
 */
function formatPostalCode(value) {
  // ハイフンを除去して数字のみに
  const numbersOnly = value.replace(/[^\d]/g, '');

  // 桁数に応じて整形
  if (numbersOnly.length <= 3) {
    return numbersOnly;
  } else if (numbersOnly.length <= 7) {
    return numbersOnly.substring(0, 3) + '-' + numbersOnly.substring(3);
  } else {
    // 7桁を超える場合は7桁でカット
    const truncated = numbersOnly.substring(0, 7);
    return truncated.substring(0, 3) + '-' + truncated.substring(3);
  }
}

/**
 * 郵便番号から住所を検索してフィールドに設定する汎用関数
 * @param {string} postalCodeInputId - 郵便番号入力フィールドのID
 * @param {Object} addressConfig - 住所フィールドの設定
 *   - combined {boolean}: true=1つのフィールドに結合、false=2つに分割
 *   - address {string}: 結合時の住所フィールドID
 *   - address1 {string}: 分割時の都道府県フィールドID
 *   - address2 {string}: 分割時の市区町村フィールドID
 */
async function searchAndFillAddress(postalCodeInputId, addressConfig) {
  const postalCode = document.getElementById(postalCodeInputId).value;
  const result = await searchAddressByPostalCode(postalCode);

  if (result) {
    if (addressConfig.combined) {
      // 1つのフィールドに全住所を結合
      document.getElementById(addressConfig.address).value =
        result.address1 + result.address2 + result.address3;
    } else {
      // 2つのフィールドに分割
      document.getElementById(addressConfig.address1).value = result.address1;
      document.getElementById(addressConfig.address2).value = result.address2 + result.address3;
    }
  } else {
    // 該当する住所が無い場合は空欄
    if (addressConfig.combined) {
      document.getElementById(addressConfig.address).value = '';
    } else {
      document.getElementById(addressConfig.address1).value = '';
      document.getElementById(addressConfig.address2).value = '';
    }
  }
}

/**
 * 郵便番号入力フィールドに自動整形と住所検索を設定する汎用関数
 * @param {string} postalCodeInputId - 郵便番号入力フィールドのID
 * @param {Function} searchCallback - 7桁入力時に実行する住所検索関数
 */
function setupPostalCodeInput(postalCodeInputId, searchCallback) {
  const postalCodeInput = document.getElementById(postalCodeInputId);
  if (postalCodeInput) {
    postalCodeInput.addEventListener('input', function() {
      // 郵便番号を整形
      this.value = formatPostalCode(this.value);

      // ハイフンを除去して数字のみに
      const numbersOnly = this.value.replace(/[^\d]/g, '');

      // 7桁になったら自動で住所検索を実行
      if (numbersOnly.length === 7) {
        searchCallback();
      }
    });
  }
}

/**
 * メッセージを表示する汎用関数
 * @param {string} message - 表示するメッセージ
 * @param {string} type - メッセージタイプ（'success', 'error', 'info'など）
 * @param {string} elementId - メッセージを表示する要素のID（デフォルト: 'message'）
 * @param {number} hideDelay - メッセージを自動的に隠すまでの時間（ミリ秒、0で自動非表示なし）
 */
function showMessage(message, type = 'info', elementId = 'message', hideDelay = 0) {
  const messageEl = document.getElementById(elementId);
  if (!messageEl) {
    console.warn(`Message element with ID '${elementId}' not found`);
    return;
  }

  messageEl.textContent = message;
  messageEl.className = type;
  messageEl.style.display = 'block';

  // 自動非表示が設定されている場合
  if (hideDelay > 0) {
    setTimeout(() => {
      messageEl.style.display = 'none';
    }, hideDelay);
  }
}

/**
 * 生年月日から年齢を計算する汎用関数
 * @param {string|Date} birthday - 生年月日（文字列またはDateオブジェクト）
 * @param {Date} baseDate - 基準日（デフォルト: 今日）
 * @returns {number|null} 年齢（無効な入力の場合はnull）
 */
function calculateAge(birthday, baseDate = new Date()) {
  if (!birthday) {
    return null;
  }

  const birthDate = typeof birthday === 'string' ? new Date(birthday) : birthday;

  // 無効な日付チェック
  if (isNaN(birthDate.getTime())) {
    return null;
  }

  let age = baseDate.getFullYear() - birthDate.getFullYear();
  const monthDiff = baseDate.getMonth() - birthDate.getMonth();

  // 誕生日がまだ来ていない場合は年齢を1減らす
  if (monthDiff < 0 || (monthDiff === 0 && baseDate.getDate() < birthDate.getDate())) {
    age--;
  }

  return age >= 0 ? age : null;
}

/**
 * 生年月日入力フィールドから年齢を計算して年齢フィールドに設定する汎用関数
 * @param {string} birthdayInputId - 生年月日入力フィールドのID
 * @param {string} ageInputId - 年齢入力フィールドのID
 */
function calculateAndFillAge(birthdayInputId, ageInputId) {
  const birthdayInput = document.getElementById(birthdayInputId);
  const ageInput = document.getElementById(ageInputId);

  if (!birthdayInput || !ageInput) {
    console.warn('Birthday or age input element not found');
    return;
  }

  const age = calculateAge(birthdayInput.value);
  ageInput.value = age !== null ? age : '';
}

/**
 * 要素の表示/非表示を切り替える汎用関数
 * @param {string} elementId - 要素のID
 * @param {boolean} show - trueで表示、falseで非表示
 */
function toggleElementVisibility(elementId, show) {
  const element = document.getElementById(elementId);
  if (element) {
    element.style.display = show ? 'block' : 'none';
  }
}

/**
 * チェックボックスの状態に応じてフィールドの有効/無効を切り替える汎用関数
 * @param {string} checkboxId - チェックボックスのID
 * @param {string[]} fieldIds - 制御するフィールドのID配列
 * @param {boolean} clearOnDisable - 無効化時にフィールドをクリアするか（デフォルト: true）
 */
function toggleFieldsByCheckbox(checkboxId, fieldIds, clearOnDisable = true) {
  const checkbox = document.getElementById(checkboxId);
  if (!checkbox) {
    console.warn(`Checkbox with ID '${checkboxId}' not found`);
    return;
  }

  const isEnabled = checkbox.checked;

  fieldIds.forEach(fieldId => {
    const field = document.getElementById(fieldId);
    if (field) {
      field.readOnly = !isEnabled;
      if (!isEnabled && clearOnDisable) {
        field.value = '';
      }
    }
  });
}

/**
 * セレクトボックスの値に応じてフィールドの有効/無効を切り替える汎用関数
 * @param {string} selectId - セレクトボックスのID
 * @param {string} targetValue - フィールドを有効化する値
 * @param {string[]} fieldIds - 制御するフィールドのID配列
 * @param {boolean} clearOnDisable - 無効化時にフィールドをクリアするか（デフォルト: true）
 */
function toggleFieldsBySelect(selectId, targetValue, fieldIds, clearOnDisable = true) {
  const select = document.getElementById(selectId);
  if (!select) {
    console.warn(`Select with ID '${selectId}' not found`);
    return;
  }

  const isEnabled = select.value === targetValue;

  fieldIds.forEach(fieldId => {
    const field = document.getElementById(fieldId);
    if (field) {
      field.readOnly = !isEnabled;
      if (!isEnabled && clearOnDisable) {
        field.value = '';
      }
    }
  });
}

/**
 * 保険者番号のバリデーションを行う汎用関数
 * @param {string} insurerNumberInputId - 保険者番号入力フィールドのID
 * @param {string} warningElementId - 警告メッセージを表示する要素のID
 * @param {string} selectedInsurerSelectId - 選択された保険者のセレクトボックスのID（オプション）
 * @returns {boolean} バリデーション結果
 */
function validateInsurerNumber(insurerNumberInputId, warningElementId, selectedInsurerSelectId = null) {
  const insurerNumberInput = document.getElementById(insurerNumberInputId);
  const warningElement = document.getElementById(warningElementId);

  if (!insurerNumberInput || !warningElement) {
    console.warn('Insurer number field or warning element not found');
    return true;
  }

  // 保険者が選択されている場合は、新規登録フィールドのバリデーションは不要
  if (selectedInsurerSelectId) {
    const selectedInsurer = document.getElementById(selectedInsurerSelectId);
    if (selectedInsurer && selectedInsurer.value !== '') {
      warningElement.style.display = 'none';
      return true;
    }
  }

  const value = insurerNumberInput.value.trim();
  const numbersOnly = value.replace(/[^\d]/g, '');

  // 桁数チェック
  if (numbersOnly.length === 0) {
    warningElement.style.display = 'none';
    return true;
  } else if (numbersOnly.length === 6 || numbersOnly.length === 8) {
    warningElement.style.display = 'none';
    return true;
  } else {
    warningElement.style.display = 'block';
    return false;
  }
}

/**
 * セレクトボックスで保険者を選択した際に詳細フィールドを更新する汎用関数
 * @param {string} selectId - 保険者セレクトボックスのID
 * @param {Object} fieldMapping - フィールドマッピング（フィールドID: data属性名）
 */
function updateInsurerFields(selectId, fieldMapping) {
  const select = document.getElementById(selectId);
  if (!select) {
    console.warn(`Select with ID '${selectId}' not found`);
    return;
  }

  const selectedOption = select.options[select.selectedIndex];
  const isSelected = select.value !== '';

  Object.entries(fieldMapping).forEach(([fieldId, dataAttr]) => {
    const field = document.getElementById(fieldId);
    if (field) {
      field.readOnly = isSelected;
      field.value = isSelected ? (selectedOption.getAttribute(`data-${dataAttr}`) || '') : '';
    }
  });
}

/**
 * セレクトボックスと新規登録フィールドの相互排他制御を行う汎用関数
 * @param {string} selectId - セレクトボックスのID
 * @param {string[]} newFieldIds - 新規登録フィールドのID配列
 */
function toggleSelectAndNewFields(selectId, newFieldIds) {
  const select = document.getElementById(selectId);
  if (!select) {
    console.warn(`Select with ID '${selectId}' not found`);
    return;
  }

  const isSelected = select.value !== '';

  newFieldIds.forEach(fieldId => {
    const field = document.getElementById(fieldId);
    if (field) {
      field.readOnly = isSelected;
      if (isSelected) {
        field.value = '';
      }
    }
  });
}

/**
 * 新規登録フィールドに入力があった際にセレクトボックスをクリアする汎用関数
 * @param {string} newFieldId - 新規登録フィールドのID
 * @param {string} selectId - セレクトボックスのID
 */
function clearSelectOnNewFieldInput(newFieldId, selectId) {
  const newField = document.getElementById(newFieldId);
  const select = document.getElementById(selectId);

  if (!newField || !select) {
    console.warn('New field or select element not found');
    return;
  }

  if (newField.value.trim() !== '') {
    select.value = '';
  }
}
