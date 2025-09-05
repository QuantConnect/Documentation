from openai import OpenAI


class OpenAI_:

    def __init__(self, api_key, model, system_prompt):
        self._client = OpenAI(api_key=api_key)
        self._model = model
        self._system_prompt = system_prompt

    def create_prompt(self, user_prompt):
        return self._client.chat.completions.create(
            model=self._model,
            extra_body={"reasoning_effort": "medium"},
            messages=[
                {"role": "system", "content": self._system_prompt},
                {"role": "user", "content": user_prompt}
            ]
        ).choices[0].message.content
